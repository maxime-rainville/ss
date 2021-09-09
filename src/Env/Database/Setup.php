<?php

namespace MaximeRainville\SS\Env\Database;

use Symfony\Component\Console\Question\Question;

interface Setup
{

    /**
     * Value that should be stored in the SS_DATABASE_CLASS env key
     * @return string
     */
    public function getDatabaseClass(): string;

    /**
     * Value that should be stored in the SS_DATABASE_CLASS env key
     * @return string
     */
    public function getDatabaseTitle(): string;

    /**
     * List of Questions to ask the user
     * @return Question[]
     */
    public function getQuestions(array $defaults): array;

    /**
     * A package that needs to be installed for this adapter to work.
     * @return string|null
     */
    public function getPackageName(): ?string;

    /**
     * @param array $config
     * @return bool
     */
    public function testConnection(array $config): bool;

}