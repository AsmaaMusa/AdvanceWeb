import { useMemo, useState } from 'react'
import {
  CheckCircle2,
  Clock3,
  MessageSquareText,
  Search,
  ShieldCheck,
  Star,
  Trash2,
  XCircle,
} from 'lucide-react'

const initialReviews = [
  {
    id: 1,
    user: 'Olivia Martinez',
    email: 'olivia.m@email.com',
    project: 'Najd Village Growth Plan',
    rating: 5,
    status: 'Published',
    date: '2026-05-12',
    text: 'The report gave us a clear market direction and useful pricing recommendations.',
  },
  {
    id: 2,
    user: 'James Thornton',
    email: 'j.thornton@email.com',
    project: 'Al-Safa Expansion',
    rating: 4,
    status: 'Pending',
    date: '2026-05-10',
    text: 'Strong bakery expansion insights. I want to review the staffing section again.',
  },
  {
    id: 3,
    user: 'Sophie Nguyen',
    email: 'sophie.n@email.com',
    project: 'Urban Tech Positioning',
    rating: 5,
    status: 'Published',
    date: '2026-05-09',
    text: 'The SWOT and launch roadmap were easy to share with our founders.',
  },
  {
    id: 4,
    user: 'Marcus Reid',
    email: 'm.reid@email.com',
    project: 'Retail Market Fit',
    rating: 2,
    status: 'Flagged',
    date: '2026-05-07',
    text: 'Some assumptions felt too broad for our local market.',
  },
]

function formatDate(value) {
  return new Date(value).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  })
}

function statusClass(status) {
  if (status === 'Published') return 'border-emerald-200 bg-emerald-50 text-emerald-700'
  if (status === 'Pending') return 'border-amber-200 bg-amber-50 text-amber-700'
  return 'border-red-200 bg-red-50 text-red-700'
}

export default function Reviews() {
  const [reviews, setReviews] = useState(initialReviews)
  const [search, setSearch] = useState('')
  const [status, setStatus] = useState('All')

  const filteredReviews = useMemo(
    () =>
      reviews.filter((review) => {
        const matchesStatus = status === 'All' || review.status === status
        const haystack = `${review.user} ${review.email} ${review.project} ${review.text}`.toLowerCase()
        return matchesStatus && haystack.includes(search.toLowerCase())
      }),
    [reviews, search, status],
  )

  const stats = useMemo(
    () => ({
      total: reviews.length,
      published: reviews.filter((review) => review.status === 'Published').length,
      pending: reviews.filter((review) => review.status === 'Pending').length,
      average: reviews.length
        ? (reviews.reduce((sum, review) => sum + review.rating, 0) / reviews.length).toFixed(1)
        : '0.0',
    }),
    [reviews],
  )

  const updateStatus = (id, nextStatus) => {
    setReviews((current) =>
      current.map((review) => (review.id === id ? { ...review, status: nextStatus } : review)),
    )
  }

  const removeReview = (id) => {
    setReviews((current) => current.filter((review) => review.id !== id))
  }

  return (
    <div className="min-h-screen bg-[#F7F8F0]">
      <div className="flex h-[82px] items-center justify-between border-b border-[#9CD5FF] bg-[#F7F8F0] px-6">
        <div>
          <h1 className="text-[30px] font-bold leading-none text-[#355872]">Reviews</h1>
          <p className="mt-2 text-[13px] text-[#7AAACE]">
            Monitor customer feedback and moderate public testimonials
          </p>
        </div>
      </div>

      <div className="p-6">
        <div className="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
          {[
            { title: 'Total Reviews', value: stats.total, icon: MessageSquareText },
            { title: 'Published', value: stats.published, icon: ShieldCheck },
            { title: 'Pending', value: stats.pending, icon: Clock3 },
            { title: 'Average Rating', value: stats.average, icon: Star },
          ].map((card) => (
            <div
              key={card.title}
              className="rounded-[20px] border border-[#9CD5FF] bg-[#F7F8F0] p-5 shadow-[0_8px_24px_rgba(53,88,114,0.08)]"
            >
              <div className="mb-5 flex h-10 w-10 items-center justify-center rounded-xl bg-white/70 text-[#355872]">
                <card.icon size={18} />
              </div>
              <h3 className="text-[22px] font-bold leading-none text-[#355872]">{card.value}</h3>
              <p className="mt-3 text-[14px] text-[#355872]">{card.title}</p>
            </div>
          ))}
        </div>

        <div className="mb-5 flex flex-col gap-3 rounded-[22px] border border-[#9CD5FF] bg-[#F7F8F0] p-4 shadow-[0_8px_24px_rgba(53,88,114,0.08)] lg:flex-row lg:items-center lg:justify-between">
          <div className="relative w-full max-w-[460px]">
            <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-[#7AAACE]" size={17} />
            <input
              value={search}
              onChange={(event) => setSearch(event.target.value)}
              placeholder="Search reviews, users, or projects..."
              className="h-11 w-full rounded-xl border border-[#9CD5FF] bg-white/70 pl-11 pr-4 text-sm text-[#355872] outline-none focus:border-[#355872]"
            />
          </div>

          <div className="flex flex-wrap gap-2">
            {['All', 'Published', 'Pending', 'Flagged'].map((item) => (
              <button
                key={item}
                onClick={() => setStatus(item)}
                className={`h-10 rounded-xl px-4 text-sm font-semibold transition ${
                  status === item
                    ? 'bg-[#355872] text-white'
                    : 'border border-[#9CD5FF] text-[#355872] hover:border-[#355872]'
                }`}
              >
                {item}
              </button>
            ))}
          </div>
        </div>

        <div className="overflow-hidden rounded-[22px] border border-[#9CD5FF] bg-[#F7F8F0] shadow-[0_8px_24px_rgba(53,88,114,0.08)]">
          <div className="grid grid-cols-[1.25fr_1fr_110px_130px_170px] border-b border-[#9CD5FF] px-5 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#7AAACE]">
            <span>Review</span>
            <span>Project</span>
            <span>Rating</span>
            <span>Status</span>
            <span>Actions</span>
          </div>

          {filteredReviews.map((review) => (
            <div
              key={review.id}
              className="grid grid-cols-[1.25fr_1fr_110px_130px_170px] items-center gap-4 border-b border-[#9CD5FF]/70 px-5 py-4 last:border-b-0"
            >
              <div>
                <p className="font-semibold text-[#355872]">{review.user}</p>
                <p className="mt-1 text-xs text-[#7AAACE]">{review.email}</p>
                <p className="mt-3 text-sm leading-6 text-[#355872]">{review.text}</p>
                <p className="mt-2 text-xs text-[#7AAACE]">{formatDate(review.date)}</p>
              </div>

              <p className="text-sm font-medium text-[#355872]">{review.project}</p>

              <div className="flex items-center gap-1 text-[#355872]">
                <Star size={16} fill="currentColor" />
                <span className="text-sm font-bold">{review.rating}.0</span>
              </div>

              <span
                className={`w-fit rounded-full border px-3 py-1 text-xs font-semibold ${statusClass(review.status)}`}
              >
                {review.status}
              </span>

              <div className="flex items-center gap-2">
                <button
                  onClick={() => updateStatus(review.id, 'Published')}
                  className="flex h-9 w-9 items-center justify-center rounded-lg border border-emerald-200 text-emerald-700"
                  title="Publish"
                >
                  <CheckCircle2 size={16} />
                </button>
                <button
                  onClick={() => updateStatus(review.id, 'Flagged')}
                  className="flex h-9 w-9 items-center justify-center rounded-lg border border-amber-200 text-amber-700"
                  title="Flag"
                >
                  <XCircle size={16} />
                </button>
                <button
                  onClick={() => removeReview(review.id)}
                  className="flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 text-red-700"
                  title="Delete"
                >
                  <Trash2 size={16} />
                </button>
              </div>
            </div>
          ))}

          {filteredReviews.length === 0 && (
            <div className="px-5 py-12 text-center text-sm text-[#7AAACE]">
              No reviews matched your filters.
            </div>
          )}
        </div>
      </div>
    </div>
  )
}
