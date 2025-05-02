<?php

class Markdown
{
    public static function format($message)
    {
        if (empty($message)) {
            return '';
        }

        $message = htmlspecialchars($message);

        $takeouts = [
            [
                'type'      => 'code',
                'regexp'    => '/```([\s\S]+?)```/',
                'replace'   => '<pre>$1</pre>',
            ],
            [
                'type'      => 'inline',
                'regexp'    => '/`([\s\S]+?)`/',
                'replace'   => '<code>$1</code>',
            ],
            [
                'type'      => 'link',
                'regexp'    => '!(?xi)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`\!()\[\]{};:\'".,<>?«»“”‘’]))!i',
                'replace'   => '<a href="$1" target="_blank" rel="nofollow noopener noreferrer">$1</a>',
            ],
            [
                'type'      => 'postref',
                'regexp'    => '/&gt;&gt;([0-9]+)/',
                'replace'   => '<a data-post="$1">&gt;&gt;$1</a>',
            ],
            [
                'type'      => 'boardref',
                'regexp'    => '/&gt;&gt;&gt;\/([a-z_0-9]+)\//',
                'replace'   => '<a class="boardref" data-board="/$1" href="/$1">&gt;&gt;&gt;/$1/</a>',
            ]
        ];

        $takeoutStore = [];

        // типографизация
        $message = preg_replace('/(^|\s|\*|\^|\%|\~)--($|\s|\*|\^|\%|\~)/', '$1&mdash;$2', $message);
        $message = preg_replace('/(^|\s|\*|\^|\%|\~)-($|\s|\*|\^|\%|\~)/', '$1&mdash;$2', $message);
        $message = preg_replace('/(^|\s|\*|\^|\%|\~)&quot;(.+?)&quot;($|\s|\pP|\*|\^|\%|\~)/', '$1&laquo;$2&raquo;$3', $message);
        // разметка цитат
        $message = preg_replace('/&gt;&gt;\s*(.+?)\s*&lt;&lt;/m', '<blockquote>&quot;$1&quot;</blockquote>', $message);
        $message = preg_replace('/^&gt;\s*(.+)$/m', '<blockquote>&gt; $1</blockquote>', $message);
        $message = preg_replace('/^&lt;\s*(.+)$/m', '<blockquote class="revquo">&lt; $1</blockquote>', $message);
        // остальная разметка
        $message = preg_replace('/\(\(\(([^\(\)]+)\)\)\)/', '<span class="them">((($1)))</span>', $message);
        $message = preg_replace('/%%([\s\S]+?)%%/', '<mark>$1</mark>', $message);
        $message = preg_replace('/\*\*([^\*]+)\*\*/', '<b>$1</b>', $message);
        $message = preg_replace('/\*([^\*]+)\*/',   '<i>$1</i>', $message);
        $message = preg_replace('/\^\^([^\^]+)\^\^/', '<u>$1</u>', $message);
        $message = preg_replace('/\~\~([^\~]+)\~\~/',  '<del>$1</del>', $message);
        $message = preg_replace('/`(.+?)`/', '<code>$1</code>', $message);
        $message = preg_replace('/(?<![\w\/])(0x[a-f0-9]{8})(?!\w)/', '<address>$1</address>', $message);
        // легаси-разметка зачёркиваний и подчёркиваний
        $message = preg_replace('/(?<!\S)\_(?!\s)(.+?)(?<!\s)\_(?!\S)/u',  '<u>$1</u>', $message);
        $message = preg_replace('/(?<!\S)\-(?!\s)(.+?)(?<!\s)\-(?!\S)/u',  '<del>$1</del>', $message);

        $message = nl2br($message);

        foreach ($takeouts as $takeout) {
            $message = preg_replace_callback(
                $takeout['regexp'],
                function ($match) use ($takeout, &$takeoutStore) {
                    $id = '[[' . uniqid($takeout['type']) . ']]';
                    $takeoutStore[$id] = str_replace('$1', $match[1], $takeout['replace']);
                    return $id;
                },
                $message
            );
        }

        foreach ($takeoutStore as $find => $replace) {
            $message = str_replace($find, $replace, $message);
        }

        return $message;
    }
}
