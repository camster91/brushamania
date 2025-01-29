<template>
    <div class="card-body">
        <h2 class="title">School Registration</h2>
        <form action="POST">
            <div class="input-group">
                <label class="label">School Name (required) </label>
                <input type="text" class="input--style-4" name="name" required v-validate>
            </div>
            <div class="row row-space">
                <div class="col-2">
                    <label class="label">Address (required)</label>
                    <input type="text" class="input--style-4" name="address" required v-validate>
                </div>
                <div class="col-2">
                    <label class="label">City (required) </label>
                    <input type="text" class="input--style-4" name="city" required v-validate>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-2">
                    <label class="label">Province (required) </label>
                    <div class="rs-select2 js-select-simple select--no-search">
                        <select name="province">
                            <option value="Alberta">Alberta</option>
                            <option value="British Columbia">British Columbia</option>
                            <option value="Manitoba">Manitoba</option>
                            <option value="New Brunswick">New Brunswick</option>
                            <option value="Newfoundland and Labrador">Newfoundland and Labrador</option>
                            <option value="Nova Scotia">Nova Scotia</option>
                            <option value="Ontario" selected="">Ontario</option>
                            <option value="Prince Edward Islan">Prince Edward Island</option>
                            <option value="Quebec">Quebec</option>
                            <option value="Saskatchewan">Saskatchewan</option>
                            <option value="Northwest Territories">Northwest Territories</option>
                            <option value="Nunavut">Nunavut</option>
                            <option value="Yukon">Yukon</option>
                        </select>
                        <div class="select-dropdown"></div>
                    </div>
                </div>
                <div class="col-2">
                    <label class="label">Postal Code (required) </label>
                    <input type="text" class="input--style-4" name="postal_code" required v-validate>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-2">
                    <label class="label">Phone (required)</label>
                    <input type="tel" class="input--style-4" name="phone" required v-validate placeholder="(123) 456-7890">
                </div>
                <div class="col-2">
                    <label class="label">School Contact Salutation (required) </label>
                    <div class="rs-select2 js-select-simple select--no-search">
                        <select name="contact_salutation">
                            <option value="Dr">Dr</option>
                            <option value="Ms" selected="">Ms</option>
                            <option value="Miss">Miss</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Mr">Mr</option>
                        </select>
                        <div class="select-dropdown"></div>
                    </div>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-2">
                    <label class="label">School Contact First Name (required) </label>
                    <input type="text" class="input--style-4" name="contact_firstname" required v-validate>
                </div>
                <div class="col-2">
                    <label class="label">School Contact Last Name (required)</label>
                    <input type="text" class="input--style-4" name="address" required v-validate>
                </div>
            </div>
            <div class="row row-space">
                <div class="col-2">
                    <label class="label">School Email (required) </label>
                    <input type="email" class="input--style-4" name="email" required v-validate>
                </div>
                <div class="col-2">
                    <label class="label">Confirm School Email (required)</label>
                    <input type="email" class="input--style-4" name="confirm_email" required v-validate>
                </div>
            </div>
            <div class="input-group">
                <label class="label">Name of Dentist Requested (optioal) </label>
                <input type="text" class="input--style-4" name="dentist" required v-validate>
            </div>
            <div class="row row-space">
                <label class="label">Please indicate the number of classes and students that you would like to participate in Brush-a-mania:</label>
                <div class="col-2">
                    <label class="label">Total Number Of Classes (required) </label>
                    <input type="text" class="input--style-4" name="name" required v-validate="'numeric'">
                </div>
                <div class="col-2">
                    <label class="label">Total Number Of Students (required)</label>
                    <input type="text" class="input--style-4" name="address" required v-validate="'numeric'">
                </div>
            </div>
            <div class="input-group">
                <label class="label">Enter the date and time of your school presentation if known </label>
                <input type="datetime-local" class="input--style-4" name="presentation" required v-validate>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
