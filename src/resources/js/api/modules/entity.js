import resource from '../resource'
import endpoint from '../endpoint'
import QueryString from '../query-string'

export default {
    browse(data = {}) {
        let ep = endpoint.entity.browse
        let qs = QueryString(data)
        let url = ep + qs
        return resource.get(url)
    },

    read(data) {
        let ep = endpoint.entity.read
        let qs = QueryString(data)
        let url = ep + qs
        return resource.get(url)
    },

    edit(data) {
        return resource.put(endpoint.entity.edit, data)
    },

    add(data) {
        return resource.post(endpoint.entity.add, data)
    },

    delete(data) {
        return resource.delete(endpoint.entity.delete, data)
    },

}