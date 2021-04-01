import axios from "axios";
import token from "../dev/token";

export default async function auth({ next, router }) {
    let auth = await axios.get('/api/user',token()).then(r => true).catch(() => false)
    if(auth){
        return next();
    }else{
        return router.push({ name: "login" })
    }
}
