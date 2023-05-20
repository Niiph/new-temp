const links = {
    login: () => '/login_check/',
    register: () => '/register/',
    devices: (id?: string) => '/devices' + (id ? '/' + id : ''),
    devicesFullList: () => '/devices/full_list/',
    devicesSimpleList: () => '/devices/simple_list/',
    sensors: (id?: string) => '/sensors/' + (id ? id : ''),
    sensorChangeActive: (id?: string) => links.sensors(id) + '/change_active',
    deviceChangeActive: (id?: string) => links.devices(id) + '/change_active',
    deviceChangePassword: (id?: string) => links.devices(id) + '/change_password',
    deviceChangeName: (id?: string) => links.devices(id) + '/change_name'
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
