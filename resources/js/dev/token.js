export default function(){
    return {headers: {Authorization: `Bearer ${localStorage.getItem('token')}`}}
}
