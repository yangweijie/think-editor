<?php

namespace yangweijie\editor;

class Editor
{
    public static function isWin() {
        return strpos(PHP_OS, 'WIN') !== false || stripos(php_uname(), 'win') !== false;
    }

    public static function getEditorHref($filePath, $line) {
        if (self::isWin() && stripos($filePath, '/mnt') !== false) {
            $filePath = str_replace('/mnt/', '', $filePath);
            $filePathArr = explode('/', $filePath);
            $filePathArr[0] .= ':';
            $filePath = implode('/', $filePathArr);
        }
        $editor = self::getEditor($filePath, $line);
        if (empty($editor)) {
            return false;
        }

        // Check that the editor is a string, and replace the
        // %line and %file placeholders:
        if (!isset($editor['url']) || !is_string($editor['url'])) {
            throw new \Exception(
                __METHOD__ . " should always resolve to a string or a valid editor array; got something else instead."
            );
        }

        $editor['url'] = str_replace("%line", rawurlencode($line), $editor['url']);
        $editor['url'] = str_replace("%file", rawurlencode($filePath), $editor['url']);

        return $editor['url'];
    }

    public static function getEditor($filePath, $line) {
        static $editor;
        static $editors;
        if(!$editor){
            $editor = config('editor.editor', 'vscode');
        }
        if(!$editors){
            $options = config('editor.editor_options');
            $editors = [];
            foreach ($options as $key => $option) {
                $editors[$key] = $option['url'];
            }
        }
        if (!$editor || (!is_string($editor) && !is_callable($editor))) {
            return [];
        }

        if (is_string($editor) && isset($editors[$editor]) && !is_callable($editors[$editor])) {
            return [
                'ajax' => false,
                'url' => $editors[$editor],
            ];
        }

        if (is_callable($editor) || (isset($editors[$editor]) && is_callable($editors[$editor]))) {
            if (is_callable($editor)) {
                $callback = call_user_func($editor, $filePath, $line);
            } else {
                $callback = call_user_func($editors[$editor], $filePath, $line);
            }

            if (empty($callback)) {
                return [];
            }

            if (is_string($callback)) {
                return [
                    'ajax' => false,
                    'url' => $callback,
                ];
            }

            return [
                'ajax' => isset($callback['ajax']) ? $callback['ajax'] : false,
                'url' => isset($callback['url']) ? $callback['url'] : $callback,
            ];
        }

        return [];
    }

    public static function wslToRealWin($filePath)
    {
        if (self::isWin() && stripos($filePath, '/mnt') !== false) {
            $filePath = str_replace('/mnt/', '', $filePath);
            $filePathArr = explode('/', $filePath);
            $filePathArr[0] .= ':';
            $filePath = implode('/', $filePathArr);
        }
        return $filePath;
    }
}