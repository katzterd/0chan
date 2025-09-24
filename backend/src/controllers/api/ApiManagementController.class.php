<?php

class ApiManagementController extends ApiBaseController
{
    const MAX_NEW_BOARDS_PER_DAY = 2;

    /**
     * @param Board|null $board
     * @throws ApiForbiddenException
     */
    protected function assertAccess(Board $board = null)
    {
        if ($board && !$this->getUser()->canManageBoard($board)) {
            throw new ApiForbiddenException;
        }
    }

    /**
     * @Auth
     *
     * @param Board|null $board
     * @return array
     * @throws ApiForbiddenException
     * @throws Exception
     */
    public function boardAction(Board $board = null)
    {
        $this->assertAccess($board);

        if (!$board) {
            $board = Board::create()
                ->setCreateDate(Timestamp::makeNow())
                ->setOwner($this->getUser());
        }

        $response = [];

        $form = Form::create();
        $editableProperties = ['name', 'description', 'hidden', 'nsfw', 'blockRu', 'threadLimit', 'bumpLimit', 'imrequired', 'textboard', 'sage', 'identity', 'likes'];
        if (!$board->getId() || $this->getUser()->canManageAllBoards()) {
            array_unshift($editableProperties, 'dir'); // show "dir" field first
        }
        foreach ($editableProperties as $propertyName) {
            $primitive = Primitive::prototyped(Board::class, $propertyName);

            if ($primitive instanceof PrimitiveString) {
                $primitive->setImportFilter(FormHelper::makeStringFilter());
            }

            if ($propertyName == 'threadLimit') {
                $primitive->setMin(50)->setMax(200);
            }
            if ($propertyName == 'bumpLimit') {
                $primitive->setMin(200)->setMax(1000);
            }
            if ($propertyName == 'dir') {
                $primitive->setMin(1)->setMax(8);
            }
            if ($propertyName == 'name') {
                $primitive->setMin(1)->setMax(14);
            }
            if ($propertyName == 'description') {
                $primitive->setMin(0)->setMax(256);
            }

            $form->add($primitive);
        }

        // saved to modlog
        $loggedProperties = ['dir', 'name', 'hidden', 'nsfw', 'blockRu', 'threadLimit', 'bumpLimit', 'imrequired', 'textboard', 'sage', 'identity', 'likes'];

        if ($form->exists('dir')) {
            $form->get('dir')->setAllowedPattern('/^[a-z0-9]+$/');
            $form->addRule('dir', CallbackLogicalObject::create(function (Form $form) use ($board) {
                $dir = (string)$form->getValue('dir');

                if ($this->getUser()->getRole()->is(UserRole::USER)) {
                    $countCreatedRecently = Criteria::create(ModeratorLog::dao())
                        ->add(Expression::eq('eventUser', $this->getUser()))
                        ->add(Expression::eq('eventType', '1'))
                        ->add(Expression::gt('eventDate', Timestamp::makeNow()->modify('24 hours ago')))
                        ->addProjection(Projection::count('id', 'count'))
                        ->getCustom('count');

                    if ($countCreatedRecently >= self::MAX_NEW_BOARDS_PER_DAY) {
                        $form->addWrongLabel(
                            'dir',
                            sprintf('Лимит создания досок достигнут: не более %d в сутки', self::MAX_NEW_BOARDS_PER_DAY)
                        );
                        return false;
                    }
                }

                if (strlen($dir) > 0) {
                    // check format
                    if (!preg_match($form->get('dir')->getAllowedPattern(), $dir)) {
                        $form->addWrongLabel('dir', 'Только латинские буквы и цифры');
                        return false;
                    }

                    if (preg_match('/^0x/i', $dir)) {
                        $form->addWrongLabel('dir', '0x______ имена зарезервированы под адреса личностей');
                        return false;
                    }

                    if (in_array($dir, ['api', 'admin', 'res', 'src', 'images', 'static', 'loops'])) {
                        $form->addWrongLabel('dir', 'Этот путь зарезервирован');
                        return false;
                    }

                    // check if dir already exists
                    $existingBoardWithSameDir = Criteria::create(Board::dao())
                        ->add(Expression::eq('dir', $dir));
                    if ($board->getId() !== null) {
                        $existingBoardWithSameDir->add(Expression::notEq('id', $board->getId()));
                    }
                    $existingBoardWithSameDir = $existingBoardWithSameDir->get();

                    if ($existingBoardWithSameDir != null) {
                        $form->addWrongLabel('dir', 'Такой раздел уже существует');
                        return false;
                    }
                }
                return true;
            }));
        }

        foreach (['imrequired', 'textboard'] as $array) {
            $form->addRule($array, CallbackLogicalObject::create(function (Form $form) use ($board) {
                $imrequired = $form->getValue('imrequired');
                $textboard = $form->getValue('textboard');

                if ($imrequired && $textboard) {
                    $form->addWrongLabel('imrequired', 'Вы можете выбрать только один из отмеченных вариантов');
                    $form->addWrongLabel('textboard', 'Вы можете выбрать только один из отмеченных вариантов');
                    return false;
                }
                return true;
            }));
        }

        FormHelper::booleanToTernary($form);

        if ($this->isPostRequest()) {
            if (!$board->getId()) {
                $this->limitWithCaptcha('create_board', 86400, 0);
            }
            $form->import($this->getRequest()->getPost());
            $form->checkRules();

            $changes = [];
            foreach ($editableProperties as $propertyName) {
                $newValue = $form->getValueOrDefault($propertyName);
                $property = Board::proto()->getPropertyByName($propertyName);
                $getter = $property->getGetter();
                $setter = $property->getSetter();
                $oldValue = $board->{$getter}();
                if ($newValue != $oldValue) {
                    $board->{$setter}($newValue);
                    if (in_array($propertyName, $loggedProperties)) {
                        $changes[$propertyName] = ['old' => $oldValue, 'new' => $newValue];
                    }
                }
            }

            if (!$form->getErrors()) {
                $db = DBPool::getByDao(Board::dao());
                try {
                    $db->begin();

                    if ($board->getId()) {
                        Board::dao()->take($board);
                        foreach ($changes as $field => $change) {
                            ModeratorLog_Property::make($board, ModeratorLogEventType::BOARD_CHANGED)
                                ->setPropertyName($field)
                                ->setOldValue($change['old'])
                                ->setNewValue($change['new'])
                                ->add();
                        }
                    } else {
                        Board::dao()->add($board);
                        ModeratorLog::make($board, ModeratorLogEventType::BOARD_CREATED)->add();
                    }

                    $db->commit();
                } catch (Exception $e) {
                    $db->rollback();
                    throw $e;
                }
            } else {
                $response['error'] = true;
            }
        }

        $response['form'] = FormHelper::toClient(
            $form,
            $board->proto()->getPropertyReadableNames(),
            [
                'description' => 'Основная информация о доске: тематика обсуждений, правила и т.п. ' .
                    'Также рекомендуется указать здесь ID личности для связи',
                'hidden' => 'Доска не показана в списке, треды не попадают на главную',
                'nsfw' => 'Контент доски не подходит для публичного просмотра (расчлененка, эротика, аниме)',
                'blockRu' => 'Закрыть доступ к доске с российских IP-адресов для поддержания высоких норм морали в рунете',
                'threadLimit' => 'Максимальное количество тредов, свыше которого треды будут отмечаться к удалению',
                'bumpLimit' => 'Максимальное количество постов в треде, после которого он перестанет подниматься',
                'imrequired' => 'Требовать медиа для создания треда',
                'textboard' => 'Запретить прикреплять медиа',
                'sage'  => 'Добавляет на вашу доску возможность отвечать в тред, не поднимая его вверх',
                'identity' => 'Добавляет возможность при создании/ответа в тред использовать свою личность',
                'likes' => 'Позволяет оценивать посты (требует авторизации)'
            ]
        );

        $model = [];
        foreach ($editableProperties as $propertyName) {
            $property = Board::proto()->getPropertyByName($propertyName);
            $model[$propertyName] = $board->{$property->getGetter()}();
        }
        $response['board'] = $model;

        return $response;
    }

    /**
     * @Auth
     *
     * @return array
     */
    public function listAction()
    {
        $criteria = Criteria::create(Board::dao())
            ->add(Expression::isFalse('deleted'))
            ->addOrder(OrderBy::create('dir')->asc());

        if (!$this->getUser()->canManageAllBoards()) {
            $criteria->add(Expression::eq('owner', $this->getUser()));
        }

        /** @var Board[] $boards */
        $boards = $criteria->getList();

        return [
            'boards' => array_map(function (Board $b) {
                return $b->export();
            }, $boards)
        ];
    }

    /**
     * @Auth
     *
     * @param Board $board
     * @return array
     */
    public function deleteBoardAction(Board $board)
    {
        $this->assertAccess($board);

        $board
            ->setDeleted(true)
            ->setDeletedAt(Timestamp::makeNow())
        ;
        Board::dao()->take($board);

        return ['ok' => true];
    }

    /**
     * @Auth
     *
     * @param Board $board
     * @return mixed
     * @throws ApiForbiddenException
     */
    public function moderatorsAction(Board $board)
    {
        $this->assertAccess($board);

        /** @var BoardModerator[] $moderators */
        $moderators = Criteria::create(BoardModerator::dao())
            ->add(Expression::eq('board', $board))
            ->addOrder(OrderBy::create('createdAt')->desc())
            ->getList();

        $response['board'] = $board->export();
        $response['moderators'] = [];

        foreach ($moderators as $moderator) {
            $response['moderators'][] = [
                'createdAt' => $moderator->getCreatedAt()->toStamp(),
                'moderator' => $moderator->getUser()->getLogin(),
                'initiator' => $moderator->getInitiator()->getLogin(),
            ];
        }


        return $response;
    }

    /**
     * @Auth
     *
     * @param Board $board
     * @param User|null $user
     * @return array
     * @throws Exception
     */
    public function addModeratorAction(Board $board, User $user = null)
    {
        $this->assertAccess($board);

        if (!$user) {
            return [
                'ok' => false,
                'error' => 'Модератор "' . $this->getRequest()->getGetVar('user') . '" не может быть добавлен'
            ];
        }

        if ($board->hasModerator($user)) {
            return [
                'ok' => false,
                'error' => 'Модератор "' . $user->getLogin() . '" уже добавлен'
            ];
        }

        $db = DBPool::getByDao(BoardModerator::dao());
        try {
            $db->begin();

            $moderator = BoardModerator::create()
                ->setCreatedAt(Timestamp::makeNow())
                ->setBoard($board)
                ->setUser($user)
                ->setInitiator($this->getUser());

            BoardModerator::dao()->add($moderator);

            ModeratorLog_User::make($board, ModeratorLogEventType::MODERATOR_ADDED)
                ->setUser($user)
                ->add();

            $db->commit();
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollback();
            }
            if (!($e instanceof DuplicateObjectException)) {
                throw $e;
            }
        }

        return ['ok' => true];
    }

    /**
     * @Auth
     *
     * @param Board $board
     * @param User $user
     * @return array
     * @throws Exception
     */
    public function removeModeratorAction(Board $board, User $user)
    {
        $this->assertAccess($board);

        $moderator = $board->getModerator($user);

        if ($moderator instanceof BoardModerator) {
            $db = DBPool::getByDao(BoardModerator::dao());
            try {
                $db->begin();

                BoardModerator::dao()->drop($moderator);

                ModeratorLog_User::make($board, ModeratorLogEventType::MODERATOR_REMOVED)
                    ->setUser($user)
                    ->add();

                $db->commit();
            } catch (Exception $e) {
                if ($db->inTransaction()) {
                    $db->rollback();
                }
                throw $e;
            }
        }

        return ['ok' => true];
    }

    /**
     * @Auth
     *
     * @param Board $board
     * @param User $newOwner
     * @return array
     * @throws Exception
     * @internal param User $user
     */
    public function changeOwnerAction(Board $board, User $newOwner = null)
    {
        $this->assertAccess($board);
        $this->limitWithCaptcha('changeOwner', 60, 2);

        if (!$newOwner) {
            return [
                'ok' => false,
                'reason' => 'Указанный логин не существует'
            ];
        }

        $db = DBPool::getByDao(BoardModerator::dao());
        try {
            $db->begin();

            $board->setOwner($newOwner);
            Board::dao()->take($board);

            ModeratorLog_User::make($board, ModeratorLogEventType::BOARD_OWNER_CHANGED)
                ->setUser($newOwner)
                ->add();

            $db->commit();
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollback();
            }
            throw $e;
        }

        return ['ok' => true];
    }
}
