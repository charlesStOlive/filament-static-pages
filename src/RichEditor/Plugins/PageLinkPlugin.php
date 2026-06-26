<?php

namespace CharlesStOlive\FilamentStaticPages\RichEditor\Plugins;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Enums\Width;

class PageLinkPlugin implements RichContentPlugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getTipTapPhpExtensions(): array
    {
        return [];
    }

    public function getTipTapJsExtensions(): array
    {
        return [];
    }

    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('pageLink')
                ->action(arguments: '{ selectedText: $getEditor().state.doc.textBetween($getEditor().state.selection.from, $getEditor().state.selection.to) }')
                ->icon('heroicon-o-link')
                ->label('Lien vers page'),
        ];
    }

    public function getEditorActions(): array
    {
        return [
            Action::make('pageLink')
                ->label('Créer un lien')
                ->modalHeading('Insérer un lien vers une page')
                ->modalWidth(Width::Medium)
                ->fillForm(function (array $arguments): array {
                    $selectedText = $arguments['selectedText'] ?? '';
                    $cleanText = trim($selectedText);

                    return [
                        'text' => !empty($cleanText) ? $cleanText : 'Texte du lien',
                    ];
                })
                ->schema([
                    TextInput::make('text')
                        ->label('Texte du lien')
                        ->required()
                        ->placeholder('Texte à afficher'),
                    Select::make('type_lien')
                        ->label('Type de lien')
                        ->options([
                            'page' => 'Page du site',
                            'externe' => 'URL externe',
                        ])
                        ->default('page')
                        ->live()
                        ->required(),
                    Select::make('page_id')
                        ->label('Page')
                        ->options(function () {
                            $modelClass = config('filament-static-pages.model');

                            return [
                                'same_page' => 'Rester sur la page',
                                ...$modelClass::where('status', 'published')->pluck('titre', 'slug')->toArray(),
                            ];
                        })
                        ->default('same_page')
                        ->selectablePlaceholder(false)
                        ->visible(fn($get) => $get('type_lien') === 'page')
                        ->live(),
                    TextInput::make('ancre')
                        ->label('Ancre')
                        ->placeholder('ex: #ma-section')
                        ->visible(fn($get) => $get('type_lien') === 'page')
                        ->helperText('Optionnel : section spécifique de la page'),
                    TextInput::make('url_externe')
                        ->label('URL externe')
                        ->placeholder('https://exemple.com')
                        ->url()
                        ->required()
                        ->visible(fn($get) => $get('type_lien') === 'externe'),
                    Toggle::make('nouvel_onglet')
                        ->label('Ouvrir dans un nouvel onglet')
                        ->default(false)
                        ->visible(fn($get) => $get('type_lien') === 'externe' || ($get('type_lien') === 'page' && $get('page_id') !== 'same_page')),
                ])
                ->action(function (array $arguments, array $data, RichEditor $component): void {
                    if ($data['type_lien'] === 'page') {
                        if ($data['page_id'] === 'same_page') {
                            $href = !empty($data['ancre']) ? $data['ancre'] : '#';
                        } else {
                            $href = '/' . $data['page_id'];
                            if (!empty($data['ancre'])) {
                                $href .= $data['ancre'];
                            }
                        }
                    } else {
                        $href = $data['url_externe'];
                    }

                    $linkAttributes = ['href' => $href];
                    $openNewTab = false;

                    if (isset($data['nouvel_onglet']) && $data['nouvel_onglet'] === true) {
                        if (
                            $data['type_lien'] === 'externe' ||
                            ($data['type_lien'] === 'page' && $data['page_id'] !== 'same_page')
                        ) {
                            $openNewTab = true;
                        }
                    }

                    if ($openNewTab) {
                        $linkAttributes['target'] = '_blank';
                        $linkAttributes['rel'] = 'noopener noreferrer';
                    }

                    $selectedText = trim($arguments['selectedText'] ?? '');

                    if (empty($selectedText)) {
                        $linkHtml = '<a href="' . htmlspecialchars($href) . '"';
                        if ($openNewTab) {
                            $linkHtml .= ' target="_blank" rel="noopener noreferrer"';
                        }
                        $linkHtml .= '>' . htmlspecialchars($data['text']) . '</a>';

                        $component->runCommands([
                            EditorCommand::make('insertContent', arguments: [$linkHtml]),
                        ], editorSelection: $arguments['editorSelection']);
                    } else {
                        $component->runCommands([
                            EditorCommand::make('setLink', arguments: [$linkAttributes]),
                        ], editorSelection: $arguments['editorSelection']);
                    }
                }),
        ];
    }
}
