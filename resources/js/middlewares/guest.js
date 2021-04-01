import axios from "axios"
import token from "../dev/token";

export default async function guest({next, router}) {
    let auth = await axios.get('/api/user', token()).then(r => false).catch(err => true)

    if (auth){
        return next()
    }else{
        return router.push({name: "home"})
    }
}
