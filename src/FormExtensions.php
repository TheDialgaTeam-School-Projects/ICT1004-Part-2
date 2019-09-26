<?php

class FormExtensions
{
    public static function printInputValidationResult($id, $pageVariable)
    {
        if (!isset($_POST[$id]))
            return '';

        if (isset($pageVariable[$id . '_error']))
            return 'is-invalid';
        else
            return 'is-valid';
    }

    public static function printValidationResult($id, $pageVariable)
    {
        if (!isset($_POST[$id]))
            return '';

        return sprintf('<div id="validation-%s" class="%s-feedback">%s</div>', $id, isset($pageVariable[$id . '_error']) ? 'invalid' : 'valid', $pageVariable[$id . '_error'] ?? 'Looks good!');
    }
}