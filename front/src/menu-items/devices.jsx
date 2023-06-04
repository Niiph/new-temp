import * as icons from '@ant-design/icons';
import Links from 'routes/ApiRoutes';
import { Get } from 'components/ApiRequest';

const Devices = () => {
    let devices = JSON.parse(localStorage.getItem('devices')) ?? [];
    let devicesData = [];

    if (!localStorage.getItem('username')) {
        return [];
    }

    const initializeDashboard = () => {
        try {
            devicesData = devices.map((device) => {
                const deviceId = device.id;
                const deviceTitle = device.name;

                const sensorItems = device.sensors.map((sensor) => {
                    const sensorId = sensor.id;
                    const sensorTitle = sensor.name;

                    return {
                        id: sensorId,
                        title: sensorTitle,
                        type: 'item',
                        url: `/sensor/${sensorId}`,
                        breadcrumbs: false
                    };
                });

                return {
                    id: deviceId,
                    title: deviceTitle,
                    type: 'collapse',
                    url: `/device/${deviceId}`,
                    icon: icons.DashboardOutlined,
                    breadcrumbs: false,
                    children: sensorItems
                };
            });
        } catch (error) {
            console.error('Error fetching data:', error);
            throw error;
        }
    };

    const fetchData = async () => {
        try {
            const response = await Get(Links('devices'));
            localStorage.setItem('devices', JSON.stringify(response['hydra:member']));
        } catch (error) {
            console.error('Error:', error);
            throw error;
        }
    };

    initializeDashboard();
    fetchData().then();

    return {
        id: 'group-devices',
        title: 'Devices',
        type: 'group',
        breadcrumbs: false,
        children: [
            {
                id: 'devices-group',
                title: 'Devices',
                type: 'collapse',
                url: `/devices`,
                icon: icons.LoginOutlined,
                breadcrumbs: false,
                children: devicesData
            }
        ]
    };
};

export default Devices();
