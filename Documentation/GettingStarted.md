## Install

Fluid is available through composer simply run this command in your project root,
to add it into your project.

```html
composer require typo3fluid/fluid
```

## Create the basic folder structure
 
Fluid uses exactly three types of template files:

- **Templates** which are _the individual files you either render directly or resolve using a controller name and action_
- **Layouts** which are _optional, shared files that are used by Templates and which render sections from Templates_
- **Partials** which are _the shared files that can be rendered from anywhere inside Fluid and contain reusable design bits_
 
## Register Paths

Fluid uses a class type called `TemplatePaths` which you can get through the `TemplateView` and can resolve and deliver template file
paths:

```php
$view = new \TYPO3Fluid\Fluid\View\TemplateView();

$paths = $view->getTemplatePaths();
$paths->setTemplateRootPaths(array('/path/to/templates/'));
$paths->setLayoutRootPaths(array('/path/to/layouts/'));
$paths->setPartialRootPaths(array('/path/to/partials/'));
```

> **Note** that paths are _always defined as arrays_. In the default `TemplatePaths` implementation, Fluid supports lookups in multiple
> template file locations - which is very useful if you are rendering template files from another package and wish to replace just
> a few template files. By adding your own template files path _last in the paths arrays_ Fluid will check those paths _first_.

## Create a Template

Fluid template are simple html templates with additional tags that enable you to bring logic into your template:

```html
<ul>
	<f:for each="{items}" as="item">
		<li>{item.name}</li>
	</f:for>
</ul>
```

## Assign variables and render

You can set the path to the template through the ```setTemplatePathAndFilename``` method
and assign variables to the view that will be available inside the template.

```php
$paths->setTemplatePathAndFilename('path/to/template.html');
$view->assign('items', array(
	array('name' => 'foo'),
	array('name' => 'bar')
));
echo $view->render();
```