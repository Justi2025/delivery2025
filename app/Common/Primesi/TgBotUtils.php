<?php


namespace App\Common\Primesi;

trait TgBotUtils
{
    /**
     * @link https://core.telegram.org/bots/api#formatting-options
     *
     * @param string $str
     * @return string
     */
    public function escapeTgChar(string $str): string
    {
        $substitutions = [
            '_' => '\_',
            '*' => '\*',
            '[' => '\[',
            ']' => '\]',
            '(' => '\(',
            ')' => '\)',
            '~' => '\~',
            '`' => '\`',
            '>' => '\>',
            '#' => '\#',
            '+' => '\+',
            '-' => '\-',
            '=' => '\=',
            '|' => '\|',
            '{' => '\{',
            '}' => '\}',
            '.' => '\.',
            '!' => '\!'
        ];
        return strtr($str, $substitutions);
    }
}