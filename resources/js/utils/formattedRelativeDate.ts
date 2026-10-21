//This util shows minutes, hours, days, then weeks. At 30 days old, it switches to a plain date such as “Sep 21, 2026.
//it'll moslty take the created_at table's column

export function formatCreatedAt(createdAt: string): string {
    const plainDateThresholdMs = 30 * 24 * 60 * 60 * 1000

    const plainDateFormatter = new Intl.DateTimeFormat(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    })


    const date = new Date(createdAt)
    const elapsedMs = Date.now() - date.getTime()

    if (elapsedMs >= plainDateThresholdMs) {
        return plainDateFormatter.format(date)
    }

    const minutes = Math.floor(elapsedMs / (60 * 1000))
    if (minutes < 1) return 'just now'
    if (minutes < 60) return `${minutes} min ago`

    const hours = Math.floor(minutes / 60)
    if (hours < 24) return `${hours} hr ago`

    const days = Math.floor(hours / 24)
    if (days < 7) return `${days} ${days === 1 ? 'day' : 'days'} ago`

    const weeks = Math.floor(days / 7)
    return `${weeks} ${weeks === 1 ? 'week' : 'weeks'} ago`
}
