#include<iostream>
using namespace std;

int main(){
    int memory_Partition = 5;
    int Nprocesses = 5;
    
    int Memory[memory_Partition] = {100,200,500,300,50};
    int Processes[Nprocesses] = {212,417,112,426,50};

    cout << "P_num \tP_size\t Partition_num\n";

    for (int i = 0; i < Nprocesses; i++) {
        bool allocated = false;

        for (int j = 0; j < memory_Partition; j++) {
            if (Memory[j] >= Processes[i]) {
                cout << "   " << i + 1  << "\t" << Processes[i] << "\t" << j + 1 << endl;
                Memory[j] -= Processes[i];
                allocated = true;
                break;
            }
        }

        if (allocated == false) {
            cout << "   " << i + 1  << "\t" << Processes[i] << "\tNot Allocated\n";
        }
    }

    return 0;
}