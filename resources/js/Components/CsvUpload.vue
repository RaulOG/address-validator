<template>
    <div>
        <h2>Upload CSV File</h2>
        <input type="file" @change="handleFileUpload" accept=".csv" />
        <div v-if="csvData.length">
            <h3>CSV Preview</h3>
            <table>
                <thead>
                <tr>
                    <th v-for="(header, index) in csvHeaders" :key="index">{{ header }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(row, rowIndex) in csvData" :key="rowIndex">
                    <td v-for="(value, colIndex) in row" :key="colIndex">{{ value }}</td>
                </tr>
                </tbody>
            </table>

            <h3>Map Your Fields</h3>
            <div v-for="(header, index) in csvHeaders" :key="index">
                <label>{{ header }}</label>
                <select v-model="fieldMappings[header]">
                    <option v-for="(expectedField, key) in expectedFields" :key="key" :value="key">
                        {{ expectedField }}
                    </option>
                </select>
            </div>
            <button @click="submitMappings">Submit Mappings</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        // Right now , we are only expecting the address field ; we can add more fields here or make it dynamic
        // by adding more fields in the expectedFields object and send only field ids to the backend
        return {
            csvData: [],
            csvHeaders: [],
            expectedFields: {
                address: 'Address',
            },
            fieldMappings: {},
        };
    },
    methods: {
        handleFileUpload(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
                const text = e.target.result;
                this.parseCSV(text);
            };
            reader.readAsText(file);
        },
        parseCSV(text) {
            const rows = text.split('\n').map(row => row.split(','));
            this.csvHeaders = rows[0];
            this.csvData = rows.slice(1).map(row => {
                const obj = {};
                this.csvHeaders.forEach((header, index) => {
                    obj[header] = row[index];
                });
                return obj;
            });
        },
        submitMappings() {
            const payload = {
                mappings: this.fieldMappings,
                csv_data: this.csvData,
            };

            this.$http.post('/user/upload', payload)
                .then(response => {
                    console.log('Upload successful:', response.data);
                })
                .catch(error => {
                    console.error('Error uploading CSV:', error);
                });
        },
    },
};
</script>
