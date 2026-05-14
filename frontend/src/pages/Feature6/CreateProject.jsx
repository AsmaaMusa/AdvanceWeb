import { useEffect, useState } from 'react'
import api from '../../api/axios'

const initialForm = {
  user_id: '',
  name: '',
  business_name: '',
  industry: '',
}

function CreateProject() {
  const [form, setForm] = useState(initialForm)
  const [users, setUsers] = useState([])
  const [loading, setLoading] = useState(false)
  const [message, setMessage] = useState('')

  useEffect(() => {
    const loadUsers = async () => {
      const { data } = await api.get('/admin/users')
      const userList = data.users || []

      setUsers(userList)
      setForm((current) => ({
        ...current,
        user_id: current.user_id || userList[0]?.id || '',
      }))
    }

    loadUsers().catch(() => setMessage('Unable to load users.'))
  }, [])

  const updateField = (field, value) => {
    setForm((current) => ({ ...current, [field]: value }))
  }

  const handleSubmit = async (event) => {
    event.preventDefault()
    setLoading(true)
    setMessage('')

    try {
      await api.post('/admin/projects', {
        ...form,
        user_id: Number(form.user_id),
      })

      setForm({
        ...initialForm,
        user_id: users[0]?.id || '',
      })
      setMessage('Project created successfully.')
    } catch (error) {
      setMessage(error.response?.data?.message || 'Unable to create project.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <section className="space-y-6">
      <div>
        <p className="text-sm font-semibold uppercase tracking-wide text-[#7AAACE]">Feature 6</p>
        <h1 className="text-[28px] font-bold text-[#355872]">Create Project</h1>
      </div>

      <form onSubmit={handleSubmit} className="max-w-3xl space-y-4 rounded-lg border border-[#D6E8F5] bg-white p-6 shadow-sm">
        <label className="block text-sm font-semibold text-[#355872]">
          User
          <select
            value={form.user_id}
            onChange={(event) => updateField('user_id', event.target.value)}
            className="mt-2 w-full rounded-md border border-[#D6E8F5] px-3 py-2 text-[#355872] outline-none focus:border-[#7AAACE]"
            required
          >
            {users.map((user) => (
              <option key={user.id} value={user.id}>
                {user.name || user.email}
              </option>
            ))}
          </select>
        </label>

        <label className="block text-sm font-semibold text-[#355872]">
          Project Name
          <input
            value={form.name}
            onChange={(event) => updateField('name', event.target.value)}
            className="mt-2 w-full rounded-md border border-[#D6E8F5] px-3 py-2 text-[#355872] outline-none focus:border-[#7AAACE]"
            required
          />
        </label>

        <label className="block text-sm font-semibold text-[#355872]">
          Business Name
          <input
            value={form.business_name}
            onChange={(event) => updateField('business_name', event.target.value)}
            className="mt-2 w-full rounded-md border border-[#D6E8F5] px-3 py-2 text-[#355872] outline-none focus:border-[#7AAACE]"
          />
        </label>

        <label className="block text-sm font-semibold text-[#355872]">
          Industry
          <input
            value={form.industry}
            onChange={(event) => updateField('industry', event.target.value)}
            className="mt-2 w-full rounded-md border border-[#D6E8F5] px-3 py-2 text-[#355872] outline-none focus:border-[#7AAACE]"
          />
        </label>

        {message && <p className="text-sm font-semibold text-[#355872]">{message}</p>}

        <button
          type="submit"
          disabled={loading || users.length === 0}
          className="rounded-md bg-[#355872] px-5 py-2 text-sm font-semibold text-white disabled:cursor-not-allowed disabled:opacity-60"
        >
          {loading ? 'Creating...' : 'Create Project'}
        </button>
      </form>
    </section>
  )
}

export default CreateProject
