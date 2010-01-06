package lib.commands
{
	import fxs.core.commands.BaseModuleStartupCommand;
	
	import lib.view.DefaultModuleMediator;

	public class ModuleStartupCommand extends BaseModuleStartupCommand
	{
		override public function beforeExecute():void
		{
			// The next lines are needed to make the reflection work
			var defaultModuleMediator:DefaultModuleMediator;
		}
	}
}