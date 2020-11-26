import resource from '../resource'
import endpoint from '../endpoint'
import QueryString from '../query-string'

export default {
    browse(data = {}) {
        let ep = endpoint.bread.browse
        let qs = QueryString(data)
        let url = ep + qs
        return resource.get(url)
    },

    read(data) {
        let ep = endpoint.bread.read
        let qs = QueryString(data)
        let url = ep + qs
        return resource.get(url)
    },

    edit(data) {
        return resource.put(endpoint.bread.edit, data)
    },

    add(data) {
        return resource.post(endpoint.bread.add, data)
    },

    delete(data) {
        return resource.delete(endpoint.bread.delete, data)
    },

}