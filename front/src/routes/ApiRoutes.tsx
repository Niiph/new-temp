const links = {
    login: () => '/login_check/',
    register: () => '/register/',

    deviceChangeActive: (id?: string) => links.devices(id) + '/change_active',
    deviceChangePassword: (id?: string) => links.devices(id) + '/change_password',
    deviceChangeName: (id?: string) => links.devices(id) + '/change_name',
    devices: (id?: string) => '/devices' + (id ? '/' + id : ''),
    devicesFullList: () => '/devices/full_list/',
    devicesSimpleList: () => '/devices/simple_list/',

    sensors: (id?: string) => '/sensors' + (id ? '/' + id : ''),
    sensorChangeActive: (id?: string) => links.sensors(id) + '/change_active',
    sensorChangeName: (id?: string) => links.sensors(id) + '/change_name',
    sensorChangeMinimum: (id?: string) => links.sensors(id) + '/change_minimum',
    sensorChangeMaximum: (id?: string) => links.sensors(id) + '/change_maximum',
    sensorChangeAddress: (id?: string) => links.sensors(id) + '/change_address',
    sensorChangeDevice: (id?: string) => links.sensors(id) + '/change_device',
    sensorChangePin: (id?: string) => links.sensors(id) + '/change_pin'
};

const aliases = (key: keyof typeof links, id?: string): string => {
    const apiUrl = process.env.REACT_APP_API_URL;
    const link = links[key](id);

    if (apiUrl && link) {
        return `${apiUrl}${link}`;
    } else {
        throw new Error(`Invalid key: ${key}`);
    }
};

export default aliases;
