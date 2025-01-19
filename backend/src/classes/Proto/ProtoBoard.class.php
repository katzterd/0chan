<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.1.master at 2014-04-16 15:41:40                    *
 *   This file will never be generated again - feel free to edit.            *
 *****************************************************************************/

	class ProtoBoard extends AutoProtoBoard {

        public function getPropertyReadableNames() {
            return [
                'dir'           => 'Путь',
                'name'          => 'Название',
                'description'   => 'Описание',
                'hidden'        => 'Скрытая',
                'nsfw'          => 'NSFW',
                'blockRu'       => 'Без РФ',
                'threadLimit'   => 'Тред лимит',
                'bumpLimit'     => 'Бамп лимит',
                'imrequired'    => 'Изображения',
                'sage'          => 'Сажа',
                'identity'      => 'Личности',
                'likes'         => 'Рейтинг',
                'textboard'     => 'Текстовая доска'
            ];
        }

        public function getPropertyReadableName($property)
        {
            $names = $this->getPropertyReadableNames();
            return $names[$property];
        }

    }
?>
