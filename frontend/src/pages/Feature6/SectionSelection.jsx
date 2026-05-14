import { Link } from 'react-router-dom'

const sections = [
  {
    title: 'Projects List',
    description: 'View Feature 6 projects and open the edit page.',
    to: 'projects',
  },
  {
    title: 'Create Project',
    description: 'Create a project and assign it to a user.',
    to: 'create-project',
  },
]

function SectionSelection() {
  return (
    <section className="space-y-6">
      <div>
        <p className="text-sm font-semibold uppercase tracking-wide text-[#7AAACE]">Feature 6</p>
        <h1 className="text-[28px] font-bold text-[#355872]">Section Selection</h1>
      </div>

      <div className="grid gap-4 md:grid-cols-2">
        {sections.map((section) => (
          <Link
            key={section.to}
            to={section.to}
            className="rounded-lg border border-[#D6E8F5] bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-[#7AAACE]"
          >
            <h2 className="text-xl font-bold text-[#355872]">{section.title}</h2>
            <p className="mt-2 text-sm text-[#7AAACE]">{section.description}</p>
          </Link>
        ))}
      </div>
    </section>
  )
}

export default SectionSelection
