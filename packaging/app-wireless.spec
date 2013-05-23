
Name: app-wireless
Epoch: 1
Version: 1.4.36
Release: 1%{dist}
Summary: Wireless Access Point
License: GPLv3
Group: ClearOS/Apps
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base
Requires: app-accounts
Requires: app-incoming-firewall
Requires: app-groups
Requires: app-users
Requires: app-network

%description
The Wireless Access Point app provides the engine for configuring and managning wireless network interfaces on the system.

%package core
Summary: Wireless Access Point - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: app-network-core
Requires: app-incoming-firewall-core
Requires: app-radius-core
Requires: hostapd
Requires: openssl

%description core
The Wireless Access Point app provides the engine for configuring and managning wireless network interfaces on the system.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/wireless
cp -r * %{buildroot}/usr/clearos/apps/wireless/

install -d -m 0755 %{buildroot}/var/clearos/wireless
install -d -m 0755 %{buildroot}/var/clearos/wireless/backup
install -D -m 0644 packaging/hostapd.php %{buildroot}/var/clearos/base/daemon/hostapd.php

%post
logger -p local6.notice -t installer 'app-wireless - installing'

%post core
logger -p local6.notice -t installer 'app-wireless-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/wireless/deploy/install ] && /usr/clearos/apps/wireless/deploy/install
fi

[ -x /usr/clearos/apps/wireless/deploy/upgrade ] && /usr/clearos/apps/wireless/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-wireless - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-wireless-core - uninstalling'
    [ -x /usr/clearos/apps/wireless/deploy/uninstall ] && /usr/clearos/apps/wireless/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/wireless/controllers
/usr/clearos/apps/wireless/htdocs
/usr/clearos/apps/wireless/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/wireless/packaging
%exclude /usr/clearos/apps/wireless/tests
%dir /usr/clearos/apps/wireless
%dir /var/clearos/wireless
%dir /var/clearos/wireless/backup
/usr/clearos/apps/wireless/deploy
/usr/clearos/apps/wireless/language
/usr/clearos/apps/wireless/libraries
/var/clearos/base/daemon/hostapd.php
