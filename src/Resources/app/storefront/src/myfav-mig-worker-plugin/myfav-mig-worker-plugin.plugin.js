const { PluginBaseClass } = window;

export default class  MyfavMigPlugin extends PluginBaseClass {
    init() {
        this.selectedEntries = null;
        this.logEl = document.getElementById('myfav-mig-worker-log');
        this.myfavMigWorkerStartBtn = document.getElementById('myfav-mig-worker-start');
        this.pos = parseInt(document.getElementById('myfav-mig-worker-pos').innerText);
        this.state = parseInt(document.getElementById('myfav-mig-worker-state').innerText);

        this.initWorkerStartBtn();
    }

    addLogText(textToAdd) {
        this.logEl.innerHTML += textToAdd;
        this.logEl.innerHTML += '<br>';
    }

    initWorkerStartBtn() {
        this.myfavMigWorkerStartBtn.addEventListener('click', () => {
            this.workUrl = document.getElementById('myfav-mig-work-url').value;
            this.workGetNextEntryUrl = document.getElementById('myfav-mig-work-get-next-entry-to-process-url').value;
            this.auth = document.getElementById('myfav-mig-worker-auth').value;
            this.myfavMigId = document.getElementById('myfav-mig-worker-myfav-mig-id').value;
            this.selectedEntries = document.getElementById('myfav-mig-worker-selected-entries').value;
            this.resetWorker();
            this.nextRequest();
        });
    }

    resetWorker() {
        this.logEl.textContent = '';
        this.addLogText('Starte Verarbeitung');
        this.myfavMigWorkerStartBtn.setAttribute('disabled', 'disabled');
        this.myfavMigWorkerStartBtn.classList.add('btn-secondary');
        this.myfavMigWorkerStartBtn.classList.remove('btn-primary');
    }

    async nextRequest() {
        if(this.selectedEntries !== null && this.selectedEntries.length > 0) {
            await this.processEntry(this.selectedEntries);
        } else {
            this.processNextEntry();
        }
    }

    async fetchData(url) {
        const response = await fetch(url); // fetch starten

        if (!response.ok) {
            throw new Error('Fehler beim Aufruf des Workers: ' + response.status);
        }

        const data = await response.json(); // JSON parsen
        return data; // Wert direkt zurückgeben
    }

    async processEntry(nextEntry) {
        let params = new URLSearchParams({
            p: this.auth,
            myfavMigId: this.myfavMigId,
            selectedEntries: nextEntry,
            ts: Date.now(),
            rnd: Math.random().toString(36).slice(2)
        }).toString();

        let url = this.workUrl + '?' + params;
        document.getElementById('myfav-mig-worker-last-called-url').textContent = url;
        let result = await this.fetchData(url);

        if(result.status === 'category mapping not found') {
            this.addLogText('Kateogrie-Mapping nicht gefunden für SW5 Kategorie: ' + result.oldCategoryData.id + ' => ' + result.oldCategoryData.name);
            return;
        }

        if(result.status === 'Product not found.') {
            this.addLogText(result.detailMessage);
            return result;
        }

        return result;
    }

    async processNextEntry() {
        let result = await this.getNextProduct();

        if(result.status === 'Product not found.') {
            // Just continue.
        } else if(result.status !== 'success') {
            this.addLogText('Fehler bei der Verarbeitung: ' + JSON.stringify(result));
            return;
        }

        this.pos = parseInt(this.pos) + 1;
        document.getElementById('myfav-mig-worker-pos').innerText = this.pos.toString();

        window.setTimeout(() => {
            this.nextRequest();
        }, 100);
    }

    // Fetches the next product from the local store.
    async getNextProduct() {
        let params = new URLSearchParams({
            p: this.auth,
            myfavMigId: this.myfavMigId,
            pos: this.pos,
            ts: Date.now(),
            rnd: Math.random().toString(36).slice(2)
        }).toString();

        let url = this.workGetNextEntryUrl + '?' + params;
        document.getElementById('myfav-mig-worker-last-called-url').textContent = url;
        let result = await this.fetchData(url);

        if(result.status === 'end') {
            this.state = 1;
            document.getElementById('myfav-mig-worker-state').innerText = this.state;
            this.addLogText('Verarbeitung abgeschlossen.');
            return;
        //} else if(result.status === 'Product not found.') {
            // Just continue.
        } else if(result.status !== 'success') {
            this.addLogText('Es ist ein Fehler aufgetreten. Bitte die Konsole und die letzten Anfragen prüfen.');
            return;
        }

        let processorResult = await this.processEntry(result.productNumber);

        return processorResult;
    }
}