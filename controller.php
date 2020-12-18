<?php
namespace Concrete\Package\CommunityStoreFulfilmentNotifications;

use Concrete\Core\Package\Package;
use Whoops\Exception\ErrorException;
use Concrete\Core\Support\Facade\Events;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Support\Facade\Application as ApplicationFacade;

class Controller extends Package
{
    protected $pkgHandle = 'community_store_fulfilment_notifications';
    protected $appVersionRequired = '8.4';
    protected $pkgVersion = '0.9.2';

    protected $pkgAutoloaderRegistries = [
        'src/CommunityStoreFulfilmentNotifications' => '\Concrete\Package\CommunityStoreFulfilmentNotifications',
    ];

    public function getPackageDescription()
    {
        return t("Community Store Fulfilment Notifications");
    }

    public function getPackageName()
    {
        return t("Community Store Fulfilment Notifications");
    }

    public function install()
    {
        $app = ApplicationFacade::getFacadeApplication();
        $installed = $app->make(PackageService::class)->getInstalledHandles();

        if(!(is_array($installed) && in_array('community_store',$installed)) ) {
            throw new ErrorException(t('This package requires that Community Store be installed'));
        } else {
            $pkg = parent::install();
        }
    }

    public function on_start() {
        $app = ApplicationFacade::getFacadeApplication();

        $orderListener = $app->make('\Concrete\Package\CommunityStoreFulfilmentNotifications\Event\OrderStatusUpdate');
        Events::addListener('on_community_store_order_status_update', array($orderListener, 'orderStatusUpdate'));
    }
}
