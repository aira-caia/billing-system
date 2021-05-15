export default function () {
    return { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } }
}

// Token that we use for every request
