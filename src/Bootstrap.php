<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use Symfony\Component\Yaml\Yaml;
use TYPO3Fluid\Fluid\View\TemplateView;

/**
*
*/
class Bootstrap {

    public function __construct() {
        $baseUri = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
		$path = $this->getPathFromUri($baseUri);
        $rootPath = preg_replace('/(^[^\/]*)\/.*$/', '$1', $path);
        $view = $this->createView();

        switch($rootPath) {
            case 'GettingStarted':
                $templateFile = __DIR__ . '/../Templates/GettingStarted.html';
                $markdownFile = __DIR__ . '/../Documentation/' . $path . '.md';
                $view->assign('markdown', file_get_contents($markdownFile));

                break;

            default:
                $templateFile = __DIR__ . '/../Templates/' . $path . '.html';
                if (!file_exists($templateFile)) {
                    die('Template not found!: ' . 'Templates/' . $path . '.html');
                }
                break;
        }
        $view->getTemplatePaths()->setTemplatePathAndFilename($templateFile);
        $fixtures = $this->loadFixtures($path);

		$view->assign('baseUrl', $baseUri);
        $view->assign('currentPath', $path);
        $view->assignMultiple($fixtures);
        echo $view->render();
    }

	public function getPathFromUri($baseUri) {
        $requestUri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';
        $requestUri = preg_replace('/^' . preg_quote($baseUri, '/') . '/', '', $requestUri);
        $parts = explode('/', $requestUri);
        array_walk($parts, function(&$value, $key) {
            $parts = explode('-', $value);
            foreach ($parts as $key => $part) {
                $parts[$key] = ucfirst($part);
            }
            $value = implode('', $parts);
        });
        $path = implode('/', $parts);

        if(empty($path)) {
            $path = 'Index';
        }

        return $path;
    }

    public function createView() {
        $view = new TemplateView();
        $paths = new \TYPO3Fluid\Docs\Fluid\TemplatePaths();
        $view->getRenderingContext()->setTemplatePaths($paths);
        $paths->setLayoutRootPaths(array(__DIR__ . '/../Layouts/'));
        $paths->setPartialRootPaths(array(__DIR__ . '/../Partials/'));

        $view->getRenderingContext()->getViewHelperResolver()->addNamespace('d', 'TYPO3Fluid\Docs\ViewHelpers');
        $view->getRenderingContext()->setTemplateProcessors(
            array(
                new \TYPO3Fluid\Docs\TemplateProcessor\CodeTemplateProcessor()
            )
        );
        return $view;
    }

    public function loadFixtures($path) {
        $fixturesPaths = array('Global', $path);
        $fixtures = array();
        foreach ($fixturesPaths as $fixturePath) {
            $fixturePath = __DIR__ . '/../Fixtures/' . $fixturePath . '.yaml';
            if (file_exists($fixturePath)) {
                $fixtures = array_replace_recursive($fixtures, Yaml::parse(file_get_contents($fixturePath)));
            }
        }
        return $fixtures;
    }
}
