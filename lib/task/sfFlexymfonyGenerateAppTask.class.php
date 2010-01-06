<?php

class sfFlexymfonyGenerateAppTask extends sfGenerateAppTask
{
	protected function configure()
	{
		parent::configure();
		$this->namespace = 'generate';
		$this->name = 'flexymfony-app';
		$this->briefDescription = 'Generates a new Flexymfony application';
	}

	protected function execute($arguments = array(), $options = array())
	{
		parent::execute($arguments, $options);

		$app = $arguments['app'];
		$appName = str_replace(' ','',ucwords(str_replace('_',' ',$app))).'App';
		$appPropName = strtolower($appName[0]).substr($appName, 1);
		
		$appDir = sfConfig::get('sf_apps_dir').'/'.$app;
		$flexAppDir = $appDir .'/flex';
		$flexSourceDir = $flexAppDir .'/src';
		$skeletonDir = dirname(__FILE__).'/skeleton';
		$viewDir = $flexSourceDir.'/lib/view';
		
		// Create basic application structure
		$finder = sfFinder::type('any')->discard('.sf');
		$this->getFilesystem()->mirror($skeletonDir.'/app', $flexAppDir, $finder);
		$this->getFilesystem()->mirror($skeletonDir.'/gateway', $appDir.'/modules', $finder);
		
		// Start replacing tokens and renaming
		$this->getFilesystem()->replaceTokens($flexSourceDir.'/FlexymfonyApp.mxml', '##', '##', array('APP_NAME' => $appName));
		$this->getFilesystem()->replaceTokens($viewDir.'/ApplicationMediator.as', '##', '##', array(
			'APP_NAME' => $appName,
			'APP_LOWER' => $appPropName
		));
		$this->getFilesystem()->replaceTokens($flexAppDir.'/.actionScriptProperties', '##', '##', array('APP_NAME' => $appName));
		$this->getFilesystem()->replaceTokens($flexAppDir.'/.actionScriptProperties', '##', '##', array('APP_NAME' => $appName));
		$this->getFilesystem()->replaceTokens($flexAppDir.'/.project', '##', '##', array('APP_NAME' => $app));
		
		// Rename application XMLX and script files
		$this->getFilesystem()->rename($flexSourceDir.'/FlexymfonyApp.mxml', $flexSourceDir."/$appName.mxml");
		$this->getFilesystem()->rename($flexSourceDir.'/scripts/FlexymfonyApp.as', $flexSourceDir."/scripts/$appName.as");
	}
}