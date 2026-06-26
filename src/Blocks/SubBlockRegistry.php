<?php

namespace CharlesStOlive\FilamentStaticPages\Blocks;

use CharlesStOlive\FilamentStaticPages\Blocks\SubBlocks\Contracts\SubBlockContract;
use Filament\Forms\Components\Builder\Block;

class SubBlockRegistry
{
    /** @var array<string, class-string<SubBlockContract>> */
    protected static array $subBlocks = [];

    /**
     * Enregistre un sous-bloc dans le registre.
     *
     * @param class-string<SubBlockContract> $class
     */
    public static function register(string $class): void
    {
        static::$subBlocks[$class::type()] = $class;
    }

    /**
     * Retourne tous les sous-blocs enregistrés, indexés par type.
     *
     * @return array<string, class-string<SubBlockContract>>
     */
    public static function all(): array
    {
        return static::$subBlocks;
    }

    /**
     * Retourne les blocs Filament (Builder\Block) pour le composant Builder.
     *
     * @return array<Block>
     */
    public static function getFilamentBlocks(): array
    {
        return collect(static::$subBlocks)
            ->map(fn(string $class) => Block::make($class::type())
                ->label($class::label())
                ->schema($class::schema()))
            ->values()
            ->all();
    }

    /**
     * Retourne le nom de vue Blade associé à un type de sous-bloc.
     * Retourne null si le type n'est pas enregistré.
     */
    public static function viewFor(string $type): ?string
    {
        $class = static::$subBlocks[$type] ?? null;

        return $class ? $class::view() : null;
    }

    /**
     * Indique si un type de sous-bloc est enregistré.
     */
    public static function has(string $type): bool
    {
        return isset(static::$subBlocks[$type]);
    }

    /**
     * Vide le registre (utile pour les tests).
     */
    public static function flush(): void
    {
        static::$subBlocks = [];
    }
}
