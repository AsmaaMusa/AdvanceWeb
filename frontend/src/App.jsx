import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom'
import AdminGuestRoute from './components/auth/AdminGuestRoute'
import AdminRoute from './components/auth/AdminRoute'
import AdminLayout from './components/Layout/AdminLayout'
import { hasAdminSession } from './lib/adminAuth'
import Analytics from './pages/Analytics'
import Dashboard from './pages/Dashboard'
import Login from './pages/Login'
import Notifications from './pages/Notifications'
import Reports from './pages/Reports'
import Reviews from './pages/Reviews'
import Settings from './pages/Settings'
import Users from './pages/Users'

function App() {
  const isAdminAuthenticated = hasAdminSession()

  return (
    <BrowserRouter>
      <Routes>
        <Route
          path="/"
          element={<Navigate to={isAdminAuthenticated ? '/admin' : '/admin/login'} replace />}
        />

        <Route
          path="/admin/login"
          element={
            <AdminGuestRoute>
              <Login />
            </AdminGuestRoute>
          }
        />

        <Route
          path="/adminlogin"
          element={
            <AdminGuestRoute>
              <Login />
            </AdminGuestRoute>
          }
        />

        <Route
          path="/admin"
          element={
            <AdminRoute>
              <AdminLayout />
            </AdminRoute>
          }
        >
          <Route index element={<Dashboard />} />
          <Route path="users" element={<Users />} />
          <Route path="reports" element={<Reports />} />
          <Route path="reviews" element={<Reviews />} />
          <Route path="notifications" element={<Notifications />} />
          <Route path="analytics" element={<Analytics />} />
          <Route path="settings" element={<Settings />} />
        </Route>

        <Route
          path="*"
          element={<Navigate to={isAdminAuthenticated ? '/admin' : '/admin/login'} replace />}
        />
      </Routes>
    </BrowserRouter>
  )
}

export default App
