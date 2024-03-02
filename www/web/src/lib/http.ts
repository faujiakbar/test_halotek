import axios from "axios";

export default {
    config:{
        url: 'http://localhost',
        data: {},
        header: {}
    },
    method: 'GET',
    data: null,
    error: null,
    setConfig: async function(par) {
        this.config = par;
        this.config["request_uri"] = "";
        if(typeof this.config.data != 'undefined' && typeof this.config.data == "object"){
            let str = [];
            (Object.entries(this.config.data)).forEach((dt,i) => {
                str.push(dt[0]+"="+dt[1]);
            });
            this.config.request_uri = str.join("&");
        }

        if(this.method.toLowerCase() == 'get') this.config.url = this.config.url + "?" + this.config.request_uri;
    },
    _exec: async function(par){
        await this.setConfig(par);

        await new axios({
            method: this.method,
            url: this.config.url,
            data: this.config.data??{},
            headers: this.config.header
        }).then(async(r) => {
            this.data = r.data;
        })
        .catch(e => {
            this.error = e.response;
            this.error['error'] = true;
        });
    },
    _response: async function(){
        return this.data??this.error;
    },
    get: async function(par) {
        this.method = 'GET';
        await this._exec(par);

        return this._response();
    },
    post: async function(par) {
        this.method = 'POST';
        await this._exec(par);

        return this._response();
    }
}