<?php

class sfFlexymfonyGenerateModuleTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addArguments(array(
		new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
		new sfCommandArgument('module', sfCommandArgument::REQUIRED, 'The module name'),
		));

		$this->namespace        = 'generate';
		$this->name             = 'flexymfony-module';
		$this->briefDescription = 'Create a Flexymfony module';
		$this->detailedDescription = <<<EOF
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		$app    = $arguments['application'];
		$module = $arguments['module'];
		
		$appName = str_replace(' ','',ucwords(str_replace('_',' ',$app))).'App';
		$appPropName = strtolower($appName[0]).substr($appName, 1);
		$appDir = sfConfig::get('sf_apps_dir').'/'.$app;
		
		$moduleName = str_replace(' ','',ucwords(str_replace('_',' ',$module)));
		$modulePropName = strtolower($moduleName[0]).substr($moduleName, 1);
		
		$flexAppDir = $appDir .'/flex';
		$flexSourceDir = $flexAppDir .'/src';
		$skeletonDir = dirname(__FILE__).'/skeleton';
		$viewDir = $flexSourceDir.'/lib/view';
		
		$skeletonDir = dirname(__FILE__).'/skeleton';
		
		$tempModuleMediator = '__TEMP__ModuleMediator.as';
		$tempModule = '__TEMP__Module.as';
		$tempMXML = '__TEMP__Module.mxml';
		
		$newNames = str_replace('__TEMP__', $moduleName, array($tempModuleMediator, $tempModule, $tempMXML));
		$newModuleMediator = $newNames[0];
		$newModule = $newNames[1];
		$newMXML = $newNames[2];
		
		// Validate the module name
		if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $module))
		{
			throw new sfCommandException(sprintf('The module name "%s" is invalid.', $module));
		}

		if (file_exists($flexSourceDir.'/modules/'.$newMXML))
		{
			throw new sfCommandException(sprintf('The module "%s" already exists in the "%s" application.', $moduleName, $app));
		}

		$constants = array(
			'MODULE_NAME' => $moduleName,
			'MODULE_NAME_LOWER', $modulePropName
		);
				
		// Create basic module structure
		$finder = sfFinder::type('any')->discard('.sf');
		$this->getFilesystem()->mirror($skeletonDir.'/module', $flexAppDir, $finder);
		
		// Replace tokens
		$this->getFilesystem()->replaceTokens(
			$flexSourceDir.'/modules/'.$tempMXML, '##', '##', $constants
		);
		$this->getFilesystem()->replaceTokens(
			$viewDir.'/'.$tempModuleMediator, '##', '##', $constants
		);
		
		// Rename files
		$this->getFilesystem()->rename($flexSourceDir.'/modules/'.$tempMXML, $flexSourceDir.'/modules/'.$newMXML);
		$this->getFilesystem()->rename($flexSourceDir.'/modules/scripts/'.$tempModule, $flexSourceDir.'/modules/scripts/'.$newModule);
		$this->getFilesystem()->rename($viewDir.'/'.$tempModuleMediator, $viewDir.'/'.$newModuleMediator);
		
		// Add entry to configuration file
		$xml = new DOMDocument();
		$xml->load($flexAppDir.'/.actionScriptProperties');
		$modules = $xml->getElementsByTagName('modules')->item(0);
		
		/*
		 * <module 
		 * 		application="src/##APP_NAME##.mxml" 
		 * 		destPath="modules/DefaultModule.swf" 
		 * 		optimize="true" 
		 * 		sourcePath="src/modules/DefaultModule.mxml"/>
		 */
		$module = $xml->createElement('module');
		$applicationAttr = $xml->createAttribute('application');
		$applicationAttr->value = 'src/'.$appName.'.mxml';
		
		$destPathAttr = $xml->createAttribute('destPath');
		$destPathAttr->value = 'modules/'.$moduleName.'Module.swf';
		
		$optimizeAttr = $xml->createAttribute('optimize');
		$optimizeAttr->value = 'true';
		
		$sourcePathAttr = $xml->createAttribute('sourcePath');
		$sourcePathAttr->value = 'src/modules/'.$moduleName.'Module.swf';
		
		$module->appendChild($applicationAttr);
		$module->appendChild($destPathAttr);
		$module->appendChild($optimizeAttr);
		$module->appendChild($sourcePathAttr);
		
		$modules->appendChild($module);

		$xml->save(sfConfig::get('sf_cache_dir').'/.actionScriptProperties');
		
		$this->getFilesystem()->copy(sfConfig::get('sf_cache_dir').'/.actionScriptProperties', $flexAppDir.'/.actionScriptProperties');

		$config = sfYaml::load($flexSourceDir.'/config/config.yml');
		$config['modules'][$moduleName] = $moduleName.'ModuleMediator';
		$config = sfYaml::dump($config, 2);
		file_put_contents(sfConfig::get('sf_cache_dir').'/'.$app.'.flex.config.yml', $config);
		$this->getFilesystem()->copy(sfConfig::get('sf_cache_dir').'/'.$app.'.flex.config.yml', $flexSourceDir.'/config/config.yml');
		
		$moduleStartup = file_get_contents($flexSourceDir.'/lib/commands/ModuleStartupCommand.as');
		$import = "import lib.view.{$moduleName}.ModuleMediator;\n\t// {{MODULES IMPORT}}";
		$class = "var {$modulePropName}ModuleMediator:{$moduleName}ModuleMediator;\n\t\t\t// {{MODULES}}";
		
		$moduleStartup = str_replace('// {{MODULES IMPORT}}', $import, $moduleStartup);
		$moduleStartup = str_replace('// {{MODULES}}', $class, $moduleStartup);

		file_put_contents(sfConfig::get('sf_cache_dir').'/'.$app.'.ModuleStartupCommand.as', $moduleStartup);
		
		$this->getFilesystem()->copy(sfConfig::get('sf_cache_dir').'/'.$app.'.ModuleStartupCommand.as', $flexSourceDir.'/lib/commands/ModuleStartupCommand.as');		
		$this->getFileSystem()->remove(array(sfConfig::get('sf_cache_dir').'/.actionScriptProperties', sfConfig::get('sf_cache_dir').'/'.$app.'.flex.config.yml', sfConfig::get('sf_cache_dir').'/'.$app.'.ModuleStartupCommand.as'));
	}
}