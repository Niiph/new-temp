import { lazy } from 'react';

// project import
import Loadable from 'components/Loadable';
import MinimalLayout from 'layout/MinimalLayout';

// render - login
const AuthLogin = Loadable(lazy(() => import('pages/authentication/Login')));
const AuthRegister = Loadable(lazy(() => import('pages/authentication/Register')));
const Logout = Loadable(lazy(() => import('pages/authentication/Logout')));

// ==============================|| AUTH ROUTING ||============================== //

const aliases = {
    login: '/login',
    register: '/register',
    logout: '/logout'
};
export { aliases };

const LoginRoutes = {
    element: <MinimalLayout />,
    children: [
        {
            path: aliases.login,
            element: <AuthLogin />
        },
        {
            path: aliases.register,
            element: <AuthRegister />
        },
        {
            path: aliases.logout,
            element: <Logout />
        }
    ]
};

export default LoginRoutes;
//
// const LoginRoutes = [
//     <Route path={aliases.login} element={<AuthLogin/>} />,
//     <Route path={aliases.register} element={<AuthRegister/>} />
// ];
// export default LoginRoutes;
