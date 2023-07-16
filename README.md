# Information

Интеграция шкалы прогресса завершенности задачи.

## Install

1. Загрузите папки и файлы в директорию `extensions/MW_EXT_Progress`.
2. В самый низ файла `LocalSettings.php` добавьте строку:

```php
wfLoadExtension( 'MW_EXT_Progress' );
```

## Syntax

```html
{{#progress: [VALUE]|[WIDTH]}}
```
