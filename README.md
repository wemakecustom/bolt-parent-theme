Bolt Parent Theme
======================

Allows the declaration of a theme’s parent, much like Wordpress. Only support Twig templates for now.

By default, Bolt configures Twig to load files from your theme, but if not found,
it will look in [`app/theme_defaults`](https://github.com/bolt/bolt/tree/master/app/theme_defaults).
This is done using Twig’s feature of having a `twig.path`.

Normally, your Twig path will look like this:

```
 - /PATH/theme/THEME
 - /PATH/app/theme_defaults
```

With this extension, it will look like this:

```
 - /PATH/theme/THEME
 - /PATH/theme/PARENT_THEME
 - /PATH/theme/PARENT_PARENT_THEME
 - /PATH/app/theme_defaults
```

## Important details

 1. The parent theme’s config will also be merged without overwriting the child’s.
 2. The parent theme can have a parent of its own.
 3. As of now, there is no way of managing assets (CSS/JS/images). Perhaps in a future version.

## Usage

In your theme, simply declare its parent by adding this to your `config.yml`:

```yaml
parent: PARENT_THEME
```

## Author

 * [Sebastien Lavoie](http://blog.lavoie.sl/)
 * [WeMakeCustom](http://wemakecustom.com)
