import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { aliases as routeAliases } from 'routes/LoginRoutes';

const Logout = () => {
    const navigate = useNavigate();

    useEffect(() => {
        localStorage.removeItem('jwt_token');
        navigate(routeAliases.login);
    });
};

export default Logout;
