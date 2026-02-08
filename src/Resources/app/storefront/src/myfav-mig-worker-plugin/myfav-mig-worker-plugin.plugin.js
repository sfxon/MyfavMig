const { PluginBaseClass } = window;

export default class  MyfavMigPlugin extends PluginBaseClass {
    init() {
        this.logEl = document.getElementById('myfav-mig-worker-log');
        this.myfavMigWorkerStartBtn = document.getElementById('myfav-mig-worker-start');

        this.initWorkerStartBtn();
    }

    initWorkerStartBtn() {
        this.myfavMigWorkerStartBtn.addEventListener('click', (event) => {
            this.logEl.textContent = '';
            this.addLogText('Starte Verarbeitung');
            this.myfavMigWorkerStartBtn.setAttribute('disabled', 'disabled');
            this.myfavMigWorkerStartBtn.classList.add('btn-secondary');
            this.myfavMigWorkerStartBtn.classList.remove('btn-primary');
        });
    }

    addLogText(textToAdd) {
        this.logEl.textContent += textToAdd
        this.logEl.innerHTML += '<br>';
    }
}