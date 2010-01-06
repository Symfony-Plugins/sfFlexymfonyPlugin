package lib.commands
{
	import fxs.core.commands.BaseModuleStartupCommand;
	
	import lib.view.DefaultModuleMediator;
	// {{MODULES IMPORT}}

	public class ModuleStartupCommand extends BaseModuleStartupCommand
	{
		override public function beforeExecute():void
		{
			// The next lines are needed to make the reflection work
			// If you want the module generator to work properly please do not remove the comment
			// after this section
			var defaultModuleMediator:DefaultModuleMediator;
			// {{MODULES}}
			
			// Insert your code here
		}
	}
}