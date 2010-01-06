package lib.view
{
	import flash.events.MouseEvent;
	
	import fxs.core.com.modules.BaseModule;
	import fxs.core.view.BaseModuleMediator;
	
	import modules.DefaultModule;

	public class DefaultModuleMediator extends BaseModuleMediator
	{
		public static const NAME:String = "DefaultMediator";
		
		public function get defaultModule():DefaultModule
		{
			return this.viewComponent as DefaultModule;
		}
		
		public function DefaultModuleMediator(viewComponent:BaseModule)
		{
			super(NAME, viewComponent);
		}
				
		override public function listNotificationInterests():Array
		{
			return [];
		}
		
		override protected function onInitModule():void
		{
			this.defaultModule.testButton.addEventListener(MouseEvent.CLICK, onTestButtonClick);
		}
		
		public function onTestButtonClick(e:MouseEvent):void
		{
			this.goTo("this/url/does/not/exist");
		}
		
		public function executeIndex():void
		{
			this.defaultModule.setWelcome();			
		}
		public function executeNotFound():void
		{
			this.defaultModule.setNotFound();	
		}
	}
}