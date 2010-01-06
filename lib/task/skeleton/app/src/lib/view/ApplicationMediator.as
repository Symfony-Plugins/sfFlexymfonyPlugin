package lib.view
{
	import fxs.core.com.BaseApplication;
	import fxs.core.view.BaseApplicationMediator;

	public class ApplicationMediator extends BaseApplicationMediator
	{
		public function get ##APP_LOWER##():##APP_NAME##
		{
			return this.viewComponent as ##APP_NAME##;
		}
		
		public function ApplicationMediator(viewComponent:BaseApplication)
		{
			super(viewComponent);
		}
	}
}