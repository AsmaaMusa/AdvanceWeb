import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../../api/axios'

function ProjectsList() {
  const [projects, setProjects] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    const loadProjects = async () => {
      const { data } = await api.get('/admin/reports-overview')
      setProjects(data.projects || [])
    }

    loadProjects()
      .catch(() => setError('Unable to load projects.'))
      .finally(() => setLoading(false))
  }, [])

  return (
    <section className="space-y-6">
      <div className="flex flex-wrap items-center justify-between gap-3">
        <div>
          <p className="text-sm font-semibold uppercase tracking-wide text-[#7AAACE]">Feature 6</p>
          <h1 className="text-[28px] font-bold text-[#355872]">Projects List</h1>
        </div>

        <Link
          to="../create-project"
          className="rounded-md bg-[#355872] px-5 py-2 text-sm font-semibold text-white"
        >
          Add Project
        </Link>
      </div>

      <div className="overflow-hidden rounded-lg border border-[#D6E8F5] bg-white shadow-sm">
        <table className="w-full min-w-[720px] text-left">
          <thead className="bg-[#F5FAFD] text-sm text-[#7AAACE]">
            <tr>
              <th className="px-4 py-3 font-semibold">Project</th>
              <th className="px-4 py-3 font-semibold">Industry</th>
              <th className="px-4 py-3 font-semibold">Owner</th>
              <th className="px-4 py-3 font-semibold">Reports</th>
              <th className="px-4 py-3 font-semibold">Action</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-[#E8F2F8] text-sm text-[#355872]">
            {projects.map((project) => (
              <tr key={project.id}>
                <td className="px-4 py-4 font-semibold">{project.name}</td>
                <td className="px-4 py-4">{project.industry || project.business_type || 'N/A'}</td>
                <td className="px-4 py-4">{project.owner_name || 'N/A'}</td>
                <td className="px-4 py-4">{project.reports_count || 0}</td>
                <td className="px-4 py-4">
                  <Link to="../edit-project" state={{ project }} className="font-semibold text-[#7AAACE]">
                    Edit
                  </Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>

        {loading && <p className="p-5 text-sm text-[#7AAACE]">Loading projects...</p>}
        {!loading && error && <p className="p-5 text-sm font-semibold text-red-500">{error}</p>}
        {!loading && !error && projects.length === 0 && (
          <p className="p-5 text-sm text-[#7AAACE]">No projects found.</p>
        )}
      </div>
    </section>
  )
}

export default ProjectsList
