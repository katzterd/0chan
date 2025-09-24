<?php

class ApiGlobalsController extends ApiBaseController
{
    private $keys = [
        'registerRequired' => ['title' => 'Постинг после регистрации', 'description' => 'Постинг возможен только после регистрации'],
        'inviteRequired' => ['title' => 'Инвайты', 'description' => 'Регистрация возможна только при наличии инвайта'],
        'torgateRegisterRequired' => ['title' => 'Постинг после регистрации (Darknet)', 'description' => 'Постинг через Tor/I2P/Yggdrasil возможен только после регистрации'],
        'disableTorPosting' => ['title' => 'Постинг через зеркала Darknet', 'description' => 'Отключить постинг для пользователей Tor/I2P/Yggdrasil'],
        'sameMediaLimit' => ['title' => 'Лимит на одинаковые медиа', 'description' => 'Фильтр одинаковых медиа - не более 1-го одинакового медиа в N минут', 'type' => 'int', 'default' => 0],
        'postIpTimeout' => ['title' => 'Лимит на создание постов', 'description' => 'Таймаут на создание постов с одного ip-адреса - не более 1-го поста в N секунд', 'type' => 'int', 'default' => 5],
        'threadIpTimeout' => ['title' => 'Лимит на создание тредов', 'description' => 'Таймаут на создание тредов с одного ip-адреса - не более 1-го треда в N секунд', 'type' => 'int', 'default' => 300],
        'threadGlobalTimeout' => ['title' => 'Глобальный лимит тредов', 'description' => 'Глобальный таймаут на создание тредов - не более 1-го треда в N секунд', 'type' => 'int', 'default' => 5],
        'disableRegister' => ['title' => 'Отключение регистрации', 'description' => 'Отключает полностью регистрацию']
    ];

    /**
     * @throws ApiForbiddenException
     */
    protected function assertAccess()
    {
        if (!$this->getUser()->getRole()->isGlobalAdmin()) {
            throw new ApiForbiddenException;
        }
    }

    /**
     * @Auth
     * @return array
     * @throws ApiForbiddenException
     */
    public function listAction()
    {
        $this->assertAccess();

        /** @var User[] $globals */
        $globals = Criteria::create(User::dao())
            ->add(Expression::gt('role', UserRole::USER))
            ->addOrder(OrderBy::create('role')->desc())
            ->addOrder(OrderBy::create('login')->asc())
            ->getList();

        $response = [
            'globals' => []
        ];
        foreach ($globals as $global) {
            $response['globals'][] = [
                'login' => $global->getLogin(),
                'role' => $global->getRole()->getName()
            ];
        }

        return $response;
    }

    /**
     * @Auth
     * @param User $user
     * @param bool $isAdmin
     * @return array
     * @throws ApiForbiddenException
     */
    public function addAction(User $user, bool $isAdmin = false)
    {
        $this->assertAccess();

        if ($isAdmin) {
            $role = UserRole::ADMIN;
        } else {
            $role = UserRole::MODERATOR;
        }

        $user->setRoleId($role);
        User::dao()->save($user);

        return ['ok' => true];
    }

    /**
     * @param User $user
     * @return array
     * @throws ApiForbiddenException
     */
    public function removeAction(User $user)
    {
        $this->assertAccess();

        $user->setRoleId(UserRole::USER);
        User::dao()->save($user);

        return ['ok' => true];
    }

    /**
     * @Auth
     * @return array
     * @throws ApiForbiddenException
     */
    public function settingsAction()
    {
        $this->assertAccess();

        $cache = Cache::me();

        $res = [];

        $form = [];

        foreach (array_keys($this->keys) as $key) {
            $res[$key] = $cache->get($key);
            $type = 'bool';
            $default = false;

            if (isset($this->keys[$key]['type'])) {
                $type = $this->keys[$key]['type'];
            }

            if (isset($this->keys[$key]['default'])) {
                $default = $this->keys[$key]['default'];
            }

            $form[] = ['name' => $key, 'required' => true, 'type' => $type, 'title' => $this->keys[$key]['title'], 'description' => $this->keys[$key]['description'], 'default' => $default]; // TODO: Перепилить этот костыль на нативное использование форм.
        }

        return ['ok' => true, 'result' => $res, 'form' => $form];
    }

    /**
     * @Auth
     * @Post
     * @return array
     * @throws ApiForbiddenException
     */
    public function settingsUpdateAction()
    {
        $this->assertAccess();

        $post_data = $this->getRequest()->getPost();

        foreach (array_keys($this->keys) as $key) {
            if (!isset($post_data[$key])) {
                return ['ok' => false, 'errors' => ['Не все поля заполнены']];
            }
        }

        $cache = Cache::me();

        foreach (array_keys($this->keys) as $key) {
            $value = $post_data[$key];

            if ($value) {
                $cache->set($key, $value, time());
            } else {
                $cache->delete($key);
            }
        }

        return ['ok' => true];
    }

    public function spamlistGetAction()
    {
        $this->assertAccess();

        $cache = Cache::me();

        $key = 'spamlist';

        $spamlist_data = $cache->get($key);

        if ($spamlist_data) {
            $response = $spamlist_data;
        } else {
            $response = '';
        }

        return ['ok' => true, 'spamlist' => $response];
    }

    public function spamlistUpdateAction()
    {
        $this->assertAccess();

        $cache = Cache::me();

        $key = 'spamlist';

        $value = $this->getRequest()->getBody();

        if ($value) {
            $cache->set($key, $value, time());
        } else {
            $cache->delete($key);
        }

        return ['ok' => true];
    }
}
