/**
 * load vue, axios, toastr
 */

import axios from 'axios'

import Vue from 'vue'

import datepicker from 'vue2-datepicker'

import BootstrapVue from 'bootstrap-vue'

import VModal from 'vue-js-modal'

Vue.use(VModal);

Vue.use(BootstrapVue);

/**
 * Create a fresh Vue application instance.
 */

const App = new Vue({

	el: '#target',

	created: function() {

		this.getUrlList();

	},

	data: {

		urls: [],
		newUrl: {'long_url': '', 'short_url': '', 'lifetime': '', 'is_active': ''},
		fillStatistics: [],
		fillStatisticRecord:{},
		errors: []
	},

	components: { datepicker },

	delimiters: ['${', '}'],
	
	methods: {

		getUrlList() {

			let url = '/urls';

			axios.get(url).then(response => {

				this.urls = response.data;
			});
		},

		showStatistics(statistics) {

			this.fillStatistics = statistics;

			if (typeof statistics != 'undefined') {

				this.$modal.show('statistics');

			} else {

				this.$modal.show('404-modal');

			}

		},

		editUrlStatistics(record) {

			this.fillStatisticRecord = record;

			this.$refs.edit.show();
		},

		updateStatisticRecord(id) {

			let url = '/statistical-record/' + id + '/edit';

			axios.put(url, this.fillStatisticRecord).then(response => {

				this.getUrlList();
				this.fillStatisticRecord = {};
				this.errors	  = [];

				this.$refs.edit.hide();

				//toastr.success('Contact successfuly added!');

			}).catch(error => {
				this.errors = error.response
			});
		},

		deleteStatisticRecord(id) {

			let url = '/statistical-record/delete/' + id;

			axios.delete(url).then(response => { 

				this.getUrlList();

				//toastr.success('Contact successfuly deleted!');
			});
		},

		sendLongUrl() {

			let url = '/add-url';

			axios({
					method: 'post',
					url: url,
					data: {
						long_url: this.newUrl.long_url,
						lifetime: this.newUrl.lifetime,
						is_active: this.newUrl.is_active[0]
					}
				}).then(response => {
					this.getUrlList();
					this.newUrl = [];
					this.errors = [];

				//toastr.success('Your url was shortened!');

			}).catch(error => {
				this.errors = error.response.data
			});
		},

		onReset(evt) {

		    evt.preventDefault();
		    /* Reset our form values */
		    this.newUrl.long_url = '';
		    this.newUrl.lifetime = '';
		    this.newUrl.is_active = false;
		    /* Trick to reset/clear native browser form validation state */
		    this.show = false;
		    this.$nextTick(() => { this.show = true });

		},

		onResetEditStatisticRecord(evt) {

			evt.preventDefault();
		    /* Reset our form values */
		    this.fillStatisticRecord.timestamp = '';
		    this.fillStatisticRecord.referrer = '';
		    this.fillStatisticRecord.ip = '';
		    this.fillStatisticRecord.browser = '';
		   
		    /* Trick to reset/clear native browser form validation state */
		    this.show = false;
		    this.$nextTick(() => { this.show = true });

		}

	}
});