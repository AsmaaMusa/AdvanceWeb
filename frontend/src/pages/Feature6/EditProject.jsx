import { useLocation, useNavigate } from 'react-router-dom'

function EditProject() {
  const navigate = useNavigate()
  const { state } = useLocation()
  const project = state?.project

  return (
    <section className="space-y-6">
      <div>
        <p className="text-sm font-semibold uppercase tracking-wide text-[#7AAACE]">Feature 6</p>
        <h1 className="text-[28px] font-bold text-[#355872]">Edit Project</h1>
      </div>

      <div className="max-w-3xl rounded-lg border border-[#D6E8F5] bg-white p-6 shadow-sm">
        <div className="grid gap-4 md:grid-cols-2">
          <label className="block text-sm font-semibold text-[#355872]">
            Project Name
            <input
              value={project?.name || ''}
              readOnly
              className="mt-2 w-full rounded-md border border-[#D6E8F5] px-3 py-2 text-[#355872] outline-none"
            />
          </label>

          <label className="block text-sm font-semibold text-[#355872]">
            Industry
            <input
              value={project?.industry || project?.business_type || ''}
              readOnly
              className="mt-2 w-full rounded-md border border-[#D6E8F5] px-3 py-2 text-[#355872] outline-none"
            />
          </label>
        </div>

        <p className="mt-5 text-sm text-[#7AAACE]">
          Project editing can be connected when an update endpoint is available.
        </p>

        <button
          type="button"
          onClick={() => navigate(-1)}
          className="mt-5 rounded-md bg-[#355872] px-5 py-2 text-sm font-semibold text-white"
        >
          Back
        </button>
      </div>
    </section>
  )
}

export default EditProject
