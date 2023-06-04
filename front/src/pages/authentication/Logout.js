import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { aliases as routeAliases } from 'routes/LoginRoutes';
import Cookies from 'js-cookie';
import { Post } from 'components/ApiRequest';
import Links from 'routes/ApiRoutes';

const Logout = () => {
    const navigate = useNavigate();

    useEffect(() => {
        localStorage.clear();
        Cookies.remove('token');
        Post(Links('tokenInvalidate'), {});
        navigate(routeAliases.login);
    }, []);
};

export default Logout;
