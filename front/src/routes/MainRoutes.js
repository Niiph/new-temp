import { lazy } from 'react';

// project import
import Loadable from 'components/Loadable';
import MainLayout from 'layout/MainLayout';

// render - devices
const DashboardDefault = Loadable(lazy(() => import('pages/dashboard')));

// render - sample page
const SamplePage = Loadable(lazy(() => import('pages/extra-pages/SamplePage')));

// render - utilities
const Typography = Loadable(lazy(() => import('pages/components-overview/Typography')));
const Color = Loadable(lazy(() => import('pages/components-overview/Color')));
const Shadow = Loadable(lazy(() => import('pages/components-overview/Shadow')));
const AntIcons = Loadable(lazy(() => import('pages/components-overview/AntIcons')));

const Device = Loadable(lazy(() => import('pages/device/DeviceView')));
const Devices = Loadable(lazy(() => import('pages/device/DeviceListView')));
const Sensor = Loadable(lazy(() => import('pages/sensor/SensorView')));

// ==============================|| MAIN ROUTING ||============================== //
const aliases = {
    home: '/',
    dashboard: '/dashboard',
    profile: '/profile',
    devices: '/devices',
    device: '/device/:id',
    sensor: '/sensor/:id',
    notFound: '*'
};
export { aliases };

const MainRoutes = {
    element: <MainLayout />,
    children: [
        {
            path: aliases.home,
            element: <DashboardDefault />
        },
        {
            path: aliases.devices,
            element: <Devices />
        },
        {
            path: aliases.device,
            element: <Device />
        },
        {
            path: aliases.sensor,
            element: <Sensor />
        },
        {
            path: '/color',
            element: <Color />
        },
        {
            path: '/sample-page',
            element: <SamplePage />
        },
        {
            path: '/shadow',
            element: <Shadow />
        },
        {
            path: '/typography',
            element: <Typography />
        },
        {
            path: '/icons/ant',
            element: <AntIcons />
        },
        {
            path: aliases.profile,
            element: <SamplePage />
        }
    ]
};

export default MainRoutes;
