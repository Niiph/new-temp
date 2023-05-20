const links = {
    login: '/login_check/',
    register: '/register/',
    devices: '/devices/',
    devicesFullList: '/devices/full_list/',
    sensors: '/sensors/'
};

const aliases = (key: keyof typeof links): string => {
    const apiUrl = process.env.REACT_APP_API_URL;
    const link = links[key];

    if (apiUrl && link) {
        return `${apiUrl}${link}`;
    } else {
        throw new Error(`Invalid key: ${key}`);
    }
};

export default aliases;
