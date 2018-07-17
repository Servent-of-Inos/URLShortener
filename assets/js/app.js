/**
 * load vue, axios, toastr
 */

import axios from 'axios'

import Vue from 'vue'

import datepicker from 'vue2-datepicker'

import BootstrapVue from 'bootstrap-vue'

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
		fillUrl: {'long_url': '', 'short_url': '', 'lifetime': '', 'is_active': ''},

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

		getUrl(contact) {

			this.fillContact.id = contact.id;
			this.fillContact.name = contact.name;
			this.fillContact.surname = contact.surname;
			this.fillContact.email = contact.email;
			this.fillContact.phone_number = contact.phone_number;
			this.fillContact.birthday = contact.birthday;

			$('#show').modal('show');

		},

		editUrlStatistics(contact) {

			this.fillContact.id = contact.id;
			this.fillContact.name = contact.name;
			this.fillContact.surname = contact.surname;
			this.fillContact.email = contact.email;
			this.fillContact.phone_number = contact.phone_number;
			this.fillContact.birthday = contact.birthday;

			$('#edit').modal('show');
		},

		updateUrlStatistics(id) {

			let url = 'contacts/' + id;

			axios.put(url, this.fillContact).then(response => {

				this.getUrlList();
				this.fillContact = {'id': '', 'name': '', 'surname': '', 'email': '', 'phone_number': '', 'birthday': ''};
				this.errors	  = [];

				$('#edit').modal('hide');

				toastr.success('Contact successfuly added!');

			}).catch(error => {
				this.errors = error.response.data
			});
		},

		deleteUrl(contact) {

			let url = 'contacts/' + contact.id;

			axios.delete(url).then(response => { 

				this.getUrlList();

				toastr.success('Contact successfuly deleted!');
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

				toastr.success('Your url was shortened!');

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

		}

	}
});