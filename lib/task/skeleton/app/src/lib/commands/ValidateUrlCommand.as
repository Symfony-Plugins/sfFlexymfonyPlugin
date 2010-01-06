package lib.commands
{
	import fxs.core.config.ApplicationNotifications;
	import fxs.core.controller.RequestUrlInfo;
	
	import org.puremvc.as3.interfaces.INotification;
	import org.puremvc.as3.patterns.command.SimpleCommand;

	public class ValidateUrlCommand extends SimpleCommand
	{
		override public function execute(notification:INotification):void
		{
			var url:String = notification.getBody() as String;
			var RequestInfo:RequestUrlInfo = new RequestUrlInfo(url);
			if (RequestInfo.isValid) 
			{
				this.sendNotification(ApplicationNotifications.URLRULE_VALID, RequestInfo);
			} else 
			{
				this.sendNotification(ApplicationNotifications.URLRULE_INVALID, RequestInfo);
			}
		}
	}
}