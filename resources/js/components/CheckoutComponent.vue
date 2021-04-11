<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-auto">
                <div class="alert" :class="'alert-'+ message.type" v-for="message in messages">{{
                        message.message
                    }}
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-auto">
                <img
                    src="https://tendo-static.s3-ap-southeast-1.amazonaws.com/200x200/assets/logo/tp-icon-2000x2000.png"
                    alt=""
                    width="100"
                    height="100"
                >
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="row mb-3" v-for="item in items">
                    <label for="total" class="col-sm-4 col-form-label">{{ item.title }}</label>
                    <div class="col-sm-8">
                        <input
                            type="text"
                            class="form-control"
                            id="total"
                            v-model="checkout[item.model]"
                        >
                    </div>
                </div>
                <div class="row mb-3 justify-content-center">
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mb-3" @click.prevent="submitted(checkout)">
                            Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['messages', 'transaction'],
    data() {
        return {
            items: [
                {
                    title: 'Summary',
                    model: 'summary'
                },
                {
                    title: 'Invoice #',
                    model: 'invoice'
                },
                {
                    title: 'Total',
                    model: 'total'
                }
            ],
            checkout: {
                summary: '',
                invoice: this.transaction.invoice || '',
                total: this.transaction.total || '',
            },
        }
    },
    computed: {},
    methods: {
        submitted(checkout) {
            axios.post('/api/checkout', checkout)
                .then(res => {
                    if ('redirectURL' in res.data) {
                        window.location.href = res.data.redirectURL;
                    }
                }).catch(err => {
                console.log(err);
            })
        }
    }
}
</script>
