import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { aliases as routeAliases } from 'routes/LoginRoutes';
import Cookies from 'js-cookie';

const TokenCheck = () => {
    const navigate = useNavigate();

    useEffect(() => {
        if (
            !Cookies.get('token') ||
            !localStorage.getItem('refresh_token_expiration') ||
            localStorage.getItem('refresh_token_expiration') < Date.now() / 1000
        ) {
            navigate(routeAliases.login);
        }
    }, [navigate]);

    return null;
};

export default TokenCheck;
