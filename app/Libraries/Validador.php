<?php

class Validador
{
    public static function nome(string $nome): bool
    {
        return strlen($nome) >= 3 && Funcoes::validaString($nome);
    }

    public static function email(string $email): bool
    {
        return Funcoes::validarEmail($email);
    }

    public static function senha(string $senha): bool
    {
        return strlen($senha) >= 6;
    }

    public static function confirma_senha(string $senha, string $confirma_senha): bool
    {
        return $senha === $confirma_senha;
    }

    public static function campo_obrigatorio($campo): bool
    {
        return isset($campo) && trim($campo) !== '';
    }
}
