#include <iostream>
#include <vector>
#include <algorithm>

using namespace std;

struct Process {
    int id;
    int arrival;
    int burst;
    int start;
    int completion;
    int turnaround;
    int waiting;
};

bool compareArrival(Process a, Process b) {
    return a.arrival < b.arrival;
}

int main() {
    int n;
    cout << "ادخل عدد العمليات: ";
    cin >> n;

    vector<Process> processes(n);

    for (int i = 0; i < n; i++) {
        processes[i].id = i + 1;
        cout << "ادخل وقت الوصول ومدة التنفيذ للعملية P" << i + 1 << ": ";
        cin >> processes[i].arrival >> processes[i].burst;
    }

    sort(processes.begin(), processes.end(), compareArrival);

    int currentTime = 0;

    for (int i = 0; i < n; i++) {
        processes[i].start = max(currentTime, processes[i].arrival);
        processes[i].completion = processes[i].start + processes[i].burst;
        processes[i].turnaround = processes[i].completion - processes[i].arrival;
        processes[i].waiting = processes[i].turnaround - processes[i].burst;
        currentTime = processes[i].completion;
    }

    cout << "\nالنتائج:\n";
    cout << "P\tArrival\tBurst\tStart\tCompletion\tTAT\tWaiting\n";
    for (const auto &p : processes) {
        cout << "P" << p.id << "\t" << p.arrival << "\t" << p.burst << "\t"
             << p.start << "\t" << p.completion << "\t\t" << p.turnaround
             << "\t" << p.waiting << endl;
    }

    return 0;
}
