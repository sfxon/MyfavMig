const { PluginBaseClass } = window;

export default class  MyfavMigPlugin extends PluginBaseClass {
    init() {
        this.logEl = document.getElementById('myfav-mig-worker-log');
        this.myfavMigWorkerStartBtn = document.getElementById('myfav-mig-worker-start');

        this.initWorkerStartBtn();
    }

    addLogText(textToAdd) {
        this.logEl.textContent += textToAdd
        this.logEl.innerHTML += '<br>';
    }

    initWorkerStartBtn() {
        this.myfavMigWorkerStartBtn.addEventListener('click', () => {
            this.workUrl = document.getElementById('myfav-mig-work-url').value;
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

    nextRequest() {
        let params = new URLSearchParams({
            p: this.auth,
            myfavMigId: this.myfavMigId,
            selectedEntries: this.selectedEntries,
            ts: Date.now(),
            rnd: Math.random().toString(36).slice(2)
        }).toString();
        let url = this.workUrl + '?' + params;

        console.log(url);
    }
}